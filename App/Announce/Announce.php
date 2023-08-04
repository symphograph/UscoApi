<?php

namespace App\Announce;

use App\DTO\AnnounceDTO;
use App\DTO\HallDTO;
use App\Img;
use App\ITF\AnnounceITF;
use App\Poster;
use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;
use ReflectionException;
use Symphograph\Bicycle\DB;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\JsonDecoder;

class Announce extends AnnounceDTO implements AnnounceITF
{
    public string|null  $youtubeId = '';
    public bool         $complited = false;
    public HallDTO|null $Hall;
    public Img|Poster   $Poster;
    public Img|Poster   $Sketch;
    /*public string|null  $map          = '';*/
    public string|null $sketchUrl    = '';
    public string|bool $error        = false;
    public string      $verLink      = '';
    public string      $date         = '';
    public string      $time         = '';
    public string|null $prrow        = '';
    public string|null $dateFormated = '';


    const PAYS = ['', '', 'Вход свободный', 'Билеты в продаже', 'Вход по пригласительным', 'Продажа завершена'];

    protected static function addNewAnnounce(): bool|self
    {
        $id = self::createNewID();
        if (!$id)
            return false;

        $Announce = Announce::byId(1);
        if (!$Announce)
            return false;

        $Announce->id = $id;
        $Announce->putToDB();

        return $Announce;
    }

    private static function createNewID(): int|bool
    {
        $qwe = qwe("SELECT max(id)+1 as id FROM announces");
        if (!$qwe or !$qwe->rowCount()) {
            return false;
        }
        $q = $qwe->fetchObject();
        return $q->id;
    }

    public function __set(string $name, $value): void
    {

    }

    #[Pure] public static function clone(self $q): self
    {
        $Announce = new Announce();
        foreach ($q as $k => $v) {
            $Announce->$k = $v;
        }
        return $Announce;
    }

    public static function byId(int $id): self
    {
        /*
        if(!$objectDTO = parent::byId($id)){
            return false;
        }
        */
        $selfObject = new self();
        $selfObject->bindSelf(parent::byId($id));
        $selfObject->initData();
        return $selfObject;
    }

    private function initData(): void
    {
        $this->Hall = HallDTO::byId($this->hallId);

        $this->Poster = Poster::byAnnounceId($this->id);
        $this->Sketch = Poster::byAnnounceId($this->id, true);
        if (empty($this->datetime)) {
            $this->datetime = date('Y-m-d H:i', time() + 3600 * 24);
        }
        $this->complited = (strtotime($this->datetime) < (time() + 3600 * 8));
        $this->datetime = date('Y-m-d H:i', strtotime($this->datetime));
        $this->date = date('Y-m-d', strtotime($this->datetime));
        $this->isShow = boolval($this->isShow);
        $this->getSketchUrl();
        $this->prrow = self::PAYS[$this->pay] ?? '';
        if ($this->pay == 3 and $this->complited) {
            $this->prrow = 'Продажа завершена';
        }

        $this->dateFormated = self::EvdateFormated();
    }

    private function EvdateFormated(): string
    {
        $evdate = strtotime($this->datetime);

        if (date('Y', $evdate) == date('Y', time())) {
            $evdateru = ru_date('d %bg', $evdate);
            $evtime = date('H:i', $evdate);
            return $evdateru . ' в ' . $evtime;
        } else
            return date('d.m.Y в H:i', $evdate);
    }

    /**
     * @return Announce[]
     */
    protected static function futureCachList(): array
    {
        return self::listByCaches(AnnounceCach::futureCacheList());
    }

    /**
     * @return Announce[]
     */
    protected static function allCachList(): array
    {
        return self::listByCaches(AnnounceCach::allCacheList());
    }

    /**
     * @return Announce[]
     */
    protected static function hallCachList(int $hallId): array
    {
        return self::listByCaches(AnnounceCach::hallCacheList($hallId));
    }

    /**
     * @return Announce[]
     */
    protected static function yearCachList(int $hallId): array
    {
        return self::listByCaches(AnnounceCach::yearCacheList($hallId));
    }

    private static function byCached(AnnounceCach $Announce): self|false
    {
        if (!empty($Announce->cache)) {
            return self::decodeFromJson($Announce->cache);
        }

        if (!Announce::reCache($Announce->id)) {
            return false;
        }

        $recachedAnnounce = Announce::byCache($Announce->id);
        if (!$recachedAnnounce) {
            return false;
        }
        return $recachedAnnounce;
    }

    /**
     * @param AnnounceCach[] $announceCachs
     * @return array|Announce[]
     */
    private static function listByCaches(array $announceCachs): array
    {

        /** @var array<self> $arr */
        $Announces = [];

        foreach ($announceCachs as $announceCach) {
            if(!$Announce = self::byCached($announceCach)){
                continue;
            }
            $Announces[] = $Announce;
        }

        if (count($Announces) !== count($announceCachs)) {
            throw new AppErr('some AnnounceCachs is broken');
        }
        return $Announces;
    }

    private function getSketchUrl(): void
    {
        $this->sketchUrl = Poster::getSrc($this->id, 1) ?? '';
    }

    public function putToDB(): void
    {
        qwe("START TRANSACTION");
        $params = DB::initParams(parent::byChild($this));
        DB::replace('announces', $params);
        self::reCache($this->id)
        or throw new AppErr('Err: Announce reCache');
        qwe("COMMIT");
    }

    protected static function delete(int $id): bool|PDOStatement
    {
        return qwe("DELETE FROM announces WHERE id = :id AND id != 1", ['id' => $id]);
    }

    private static function getReady(int $id): bool|Announce
    {
        $Announce = Announce::byId($id);
        if (!$Announce) return false;
        $Announce->verLink = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $Announce->Poster->verLink;
        $Announce->progName = strip_tags($Announce->progName);
        $Announce->getSketchUrl();
        $Announce->date = date('Y-m-d', strtotime($Announce->datetime));
        $Announce->time = date('H:i', strtotime($Announce->datetime));
        $Announce->initVideo();


        return $Announce;
    }

    private function initVideo(): void
    {
        $qwe = qwe("select youtubeId from video where announceId = :id", ['id' => $this->id]);
        if (!$qwe || !$qwe->rowCount()) {
            return;
        }
        $qwe = $qwe->fetchObject();
        $this->youtubeId = $qwe->youtubeId ?? '';
    }

    private function saveCache(): bool|PDOStatement
    {
        $Announce = $this;
        if (isset($Announce->cache))
            unset($Announce->cache);

        return qwe("
                    UPDATE announces 
                    SET cache = :cache 
                    WHERE id = :id", [
                'cache' => json_encode($this, JSON_HEX_QUOT),
                'id'    => $this->id
            ]
        );
    }

    public static function reCache(int $id): bool
    {
        $Announce = Announce::getReady($id);
        if (!$Announce) {
            return false;
        }

        return boolval($Announce->saveCache());

    }

    protected static function byCache(int $id, bool $tryReCache = true): bool|self
    {
        $qwe = qwe("
            SELECT cache FROM announces 
            WHERE id = :id",
            compact('id')
        );
        if (!$qwe or !$qwe->rowCount()) {
            return false;
        }
        $cache = $qwe->fetchObject()->cache;
        if (!empty($cache)) {
            return self::decodeFromJson($cache);
        }

        if ($tryReCache) {
            Announce::reCache($id);
            return Announce::byCache($id, false);
        }
        return false;
    }

    /**
     * @param string $Json
     * @return self
     * @throws ReflectionException
     */
    private static function decodeFromJson(string $Json): self
    {

        /** @var self $Announce */
        $Announce = JsonDecoder::cloneFromAny(json_decode($Json), self::class);
        return $Announce;
    }

}