<?php

namespace App;

use App\DTO\AnnounceDTO;
use App\DTO\HallDTO;
use App\ITF\AnnounceITF;
use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;
use ReflectionException;
use Symphograph\Bicycle\DB;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\JsonDecoder;

class Announce extends AnnounceDTO implements AnnounceITF
{
    public string|null  $youtubeId   = '';
    public bool         $complited    = false;
    public HallDTO|null $Hall;
    public Img|Poster   $Poster;
    public Img|Poster   $TopPoster;
    public string|null  $map          = '';
    public string|null  $topImgUrl    = '';
    public string|bool  $error        = false;
    public string       $verLink      = '';
    public string       $date         = '';
    public string       $time         = '';
    public string|null  $prrow        = '';
    public string|null  $dateFormated = '';


    const PAYS = ['', '', 'Вход свободный', 'Билеты в продаже', 'Вход по пригласительным', 'Продажа завершена'];

    public static function addNewAnnounce(): bool|self
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

    private static function checkClass(self $Object): self
    {
        return $Object;
    }

    public static function byId(int $id): self
    {
        $selfObject = new self();
        $selfObject->bindSelf(parent::byId($id));
        $selfObject->initData();
        return $selfObject;
    }

    private function initData(): void
    {
        $this->Hall = HallDTO::byId($this->hallId);

        $this->Poster = Poster::byAnnounceId($this->id);
        $this->TopPoster = Poster::byAnnounceId($this->id, true);
        if (empty($this->datetime)) {
            $this->datetime = date('Y-m-d H:i', time() + 3600 * 24);
        }
        $this->complited = (strtotime($this->datetime) < (time() + 3600 * 8));
        $this->datetime = date('Y-m-d H:i', strtotime($this->datetime));
        $this->date = date('Y-m-d', strtotime($this->datetime));
        $this->isShow = boolval($this->isShow);
        $this->getTopImgUrl();
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

    private static function collectionParams(int $sort, int $year = 0, bool $new = false): array
    {
        if (!$year)
            $year = date('Y');

        $sorts = ['announces.datetime DESC', 'announces.datetime ASC'];
        $sort = $sorts[$sort] ?? 'announces.datetime DESC';
        $curDate = '2000-01-01 00:00';
        if ($new) {
            $curDate = date('Y-m-d H:i', time() + 3600 * 8);
        }
        return [
            'sort'    => $sort,
            'year'    => $year,
            'curDate' => $curDate
        ];
    }

    public static function getCollectionByCache(int $sort, int $year = 0, bool $new = false): array
    {
        $params = self::collectionParams($sort, $year, $new);
        $qwe = qwe("
            SELECT id, datetime, cache from announces
            WHERE year(datetime) = :year
            AND announces.datetime >= :curDate
            ORDER BY " . $params['sort'],
            [
                'year'    => $params['year'],
                'curDate' => $params['curDate']
            ]
        );
        if (!$qwe || !$qwe->rowCount())
            return [];

        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS, get_class());

        /** @var array<self> $arr */
        $arr = [];

        foreach ($qwe as $Announce) {
           // $Announce = self::checkClass($Announce);
            if (!empty($Announce->cache)) {
                $arr[] = self::decodeFromJson($Announce->cache);
                continue;
            }

            if (!Announce::reCache($Announce->id)) {
                continue;
            }

            $recachedAnnounce = Announce::byCache($Announce->id);
            if (!$recachedAnnounce) {
                continue;
            }

            $arr[] = $recachedAnnounce;
        }

        if (count($arr) === count($qwe)) {
            return $arr;
        }
        return [];
    }

    public static function apiValidation(): array
    {
        return [
            'year' => intval($_POST['year'] ?? date('Y')),
            'sort' => intval($_POST['sort'] ?? 0),
            'new'  => boolval($_POST['new'] ?? false)
        ];
    }

    private function getTopImgUrl(): void
    {
        $url = Poster::getSrc($this->id, 1);
        if ($url) {
            $this->topImgUrl = $url;
        }

        /*
        $file = 'img/afisha/'.$this->topimg;
        if(!file_exists($file)){
            $file = 'img/afisha/deftop3.jpg';
        }
        $img = new Img('img/afisha/'.$this->topimg);
        $this->topImgUrl = $img->verLink;
        */
    }

    public static function setByPost(): self|bool
    {
        if (empty($_POST['announce']))
            return false;

        $data = (object)$_POST['announce'];

        $Announce = new self();
        foreach ($Announce as $k => $v) {
            if (!empty($data->$k)) {
                $Announce->$k = $data->$k;
            }
        }
        return $Announce;
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

    public static function delete(int $id): bool|PDOStatement
    {
        return qwe("DELETE FROM announces WHERE id = :id AND id != 1", ['id' => $id]);
    }

    public static function getReady(int $id): bool|Announce
    {
        $Announce = Announce::byId($id);
        if (!$Announce) return false;
        $Announce->verLink = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $Announce->Poster->verLink;
        $Announce->progName = strip_tags($Announce->progName);
        $Announce->getTopImgUrl();
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

    public function saveCache(): bool|PDOStatement
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

    public static function byCache(int $id, bool $tryReCache = true): bool|self
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