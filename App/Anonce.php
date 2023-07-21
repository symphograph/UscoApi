<?php

namespace App;

use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;
use ReflectionException;
use Symphograph\Bicycle\DB;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\JsonDecoder;

class Announce
{
    public int|null     $id           = 0;
    public int|null     $hallId       = 8;
    public string|null  $prog_name    = 'Название';
    public string|null  $sdescr       = '';
    public string|null  $description  = 'Описание';
    public string|null  $img          = '';
    public string|null  $topimg       = 'deftop3.jpg';
    public string|null  $datetime     = '';
    public int|null     $pay          = 0;
    public int          $age          = 0;
    public string|null  $ticket_link  = '';
    public string|null  $hall_name    = '';
    public string|null  $youtube_id   = '';
    public bool         $complited    = false;
    public Hall|null    $Hall;
    public Img|Poster   $Poster;
    public Img|Poster   $TopPoster;
    public string|null  $map          = '';
    public string|null  $topImgUrl    = '';
    public string|bool  $error        = false;
    public int|bool     $show         = false;
    public string       $verLink      = '';
    public string       $date         = '';
    public string       $time         = '';
    public string|null  $prrow        = '';
    public string|null  $dateFormated = '';
    private string|null $cache;

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
        if (!$Announce->putToDB())
            return false;

        return $Announce;
    }

    private static function createNewID(): int|bool
    {
        $qwe = qwe("SELECT max(id)+1 as id FROM anonces");
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

    public static function byQ(self $q): Announce|bool
    {
        $Announce = self::checkClass($q);

        $Announce->Hall = new Hall(
            id: $Announce->hallId,
            name: $Announce->hall_name,
            map: $Announce->map
        );

        $Announce->Poster = Poster::byAnnounceId($Announce->id);
        $Announce->TopPoster = Poster::byAnnounceId($Announce->id, true);
        if (empty($Announce->datetime)) {
            $Announce->datetime = date('Y-m-d H:i', time() + 3600 * 24);
        }
        $Announce->complited = (strtotime($Announce->datetime) < (time() + 3600 * 8));
        $Announce->datetime = date('Y-m-d H:i', strtotime($Announce->datetime));
        $Announce->date = date('Y-m-d', strtotime($Announce->datetime));
        $Announce->show = boolval($Announce->show);
        $Announce->getTopImgUrl();
        $Announce->prrow = self::PAYS[$Announce->pay] ?? '';
        if ($Announce->pay == 3 and $Announce->complited) {
            $Announce->prrow = 'Продажа завершена';
        }

        $Announce->dateFormated = $Announce->EvdateFormated();

        return $Announce;

    }

    public static function byId(int $id): bool|self
    {

        $qwe = qwe("
            SELECT
            a.*,
            a.id,
            h.name,
            h.map
            FROM
            anonces as a
            INNER JOIN halls as h ON a.hallId = h.id
            WHERE a.id = :id
            ", ['id' => $id]);

        if (!$qwe or !$qwe->rowCount())
            return false;

        $q = $qwe->fetchObject(get_class());
        return Announce::byQ($q);
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

    private function fDate(): string
    {
        return date('d.m.Y', strtotime($this->datetime));
    }

    private function fTime(): string
    {
        return date('H:i', strtotime($this->datetime));
    }

    public function getProgNameClean(): string
    {
        $progName = str_replace('<br>', ' ', $this->prog_name);
        $progName = strip_tags($progName);
        return preg_replace('/^ +| +$|( ) +/m', '$1', $progName);
    }

    private static function collectionParams(int $sort, int $year = 0, bool $new = false): array
    {
        if (!$year)
            $year = date('Y');

        $sorts = ['anonces.datetime DESC', 'anonces.datetime ASC'];
        $sort = $sorts[$sort] ?? 'anonces.datetime DESC';
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

    public static function getCollection(int $sort, int $year = 0, bool $new = false): array|bool
    {
        $params = self::collectionParams($sort, $year, $new);
        $qwe = qwe("
        SELECT    
        anonces.id,
        anonces.hallId,
        anonces.prog_name,
        anonces.sdescr,
        anonces.img,
        anonces.topimg,
        anonces.aftitle,
        anonces.datetime,
        anonces.pay,
        anonces.age,
        anonces.ticket_link,
        anonces.`show`,
        halls.name,
        halls.map,
        video.youtube_id
        FROM
        anonces
        INNER JOIN halls ON anonces.hallId = halls.id
            AND anonces.datetime >= :curDate
        LEFT JOIN video ON anonces.id = video.announceId
        WHERE year(datetime) = :year
        ORDER BY " . $params['sort'], [
                'curDate' => $params['curDate'],
                'year'    => $params['year']
            ]
        );

        if (!$qwe or !$qwe->rowCount()) {
            return false;
        }

        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS, "Announce");
        //printr($qwe);
        $arr = [];
        foreach ($qwe as $q) {
            $Announce = Announce::byQ($q);
            if (!$Announce) continue;
            $arr[] = $Announce;
        }


        return $arr;
    }

    public static function getCollectionByCache(int $sort, int $year = 0, bool $new = false): array
    {
        $params = self::collectionParams($sort, $year, $new);
        $qwe = qwe("
            SELECT id, datetime, cache from anonces
            WHERE year(datetime) = :year
            AND anonces.datetime >= :curDate
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
            $Announce = self::checkClass($Announce);
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
        if (empty($_POST['evdata']))
            return false;

        $data = (object)$_POST['evdata'];

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
        $params = DB::initParams($this);
        self::reCache($this->id)
            or throw new AppErr('Err: Announce putToDB');
        qwe("COMMIT");
    }

    public static function delete(int $id): bool|PDOStatement
    {
        return qwe("DELETE FROM anonces WHERE id = :id AND id != 1", ['id' => $id]);
    }

    public function getHtml(): string
    {
        $host = 'https://' . $_SERVER['SERVER_NAME'] . '/';
        ob_start();
        ?>
        <div class="eventboxl">
            <div class="eventbox">
                <img src="<?php echo $this->Poster->verLink ?>" width="100%" alt="изображение">
            </div>
            <div class="eventboxin">
                <div class="text">
                    <div class="eventcol"></div>
                    <p><b><?php echo self::getProgNameClean() ?></b></p>
                    <br>
                    <p><b><?php echo $this->fDate() . ' ' . $this->fTime() ?></b></p>
                    <?php echo $this->description ?>
                    <?php
                    if (strtotime($this->datetime) > strtotime('2020-03-10')) {
                        ?>
                        <br><br>
                        <small>Уважаемые посетители, убедительная просьба соблюдать
                            меры безопасности в связи с распространением коронавирусной инфекции!</small>
                        <?php
                    }
                    ?>


                    <br><br>
                    Справки по тел:<br>
                    <div class="tel"><a href="tel:+74242300518">+7-4242-300-518</a></div>
                    <br>
                    <div class="tel"><a href="tel:+79624163689">+7-962-416-36-89</a></div>
                    <br>
                </div>
                <div class="share-buttons">
                    <div class="fbb">
                        <!-- Put this script tag to the <head> of your page -->
                        <script type="text/javascript" src="https://vk.com/js/api/share.js?93"
                                charset="windows-1251"></script>

                        <!-- Put this script tag to the place, where the Share button will be -->
                        <script type="text/javascript">
                            document.write(VK.Share.button({
                                text: "Поделиться",
                                title: "<?php echo $this->EvdateFormated()?>",
                                url: "<?php echo 'https://' . $_SERVER['SERVER_NAME'] . '/event.php?evid=' . $this->id?>",
                                image: "<?php echo 'https://' . $_SERVER['SERVER_NAME'] . $this->Poster->verLink?>",
                                noparse: true
                            }, {type: "round", text: "Поделиться"}));
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    public static function getReady(int $id): bool|Announce
    {
        $Announce = Announce::byId($id);
        if (!$Announce) return false;
        $Announce->verLink = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $Announce->Poster->verLink;
        $Announce->prog_name = strip_tags($Announce->prog_name);
        $Announce->getTopImgUrl();
        $Announce->date = date('Y-m-d', strtotime($Announce->datetime));
        $Announce->time = date('H:i', strtotime($Announce->datetime));
        $Announce->initVideo();


        return $Announce;
    }

    private function initVideo(): void
    {
        $qwe = qwe("select youtube_id from video where announceId = :id", ['id' => $this->id]);
        if (!$qwe || !$qwe->rowCount()) {
            return;
        }
        $qwe = $qwe->fetchObject();
        $this->youtube_id = $qwe->youtube_id ?? '';
    }

    public function saveCache(): bool|PDOStatement
    {
        $Announce = $this;
        if (isset($Announce->cache))
            unset($Announce->cache);

        return qwe("
                    UPDATE anonces 
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

    private function evDate()
    {
        ?>
        <div class="evdate" <?php if (!$this->complited) echo 'style="box-shadow:  0 1px 16px 0px #00ff09a8"' ?>>
            <?php echo self::EvdateFormated() ?>
        </div>
        <?php
    }

    private function byeButton(): string
    {
        $btnHref = 'event.php?evid=' . $this->id;
        $btnText = 'Подробно';

        if ($this->pay == 3 and !$this->complited) {
            $btnHref = $this->ticket_link;
            $btnText = 'Купить онлайн';
        }

        if ($this->complited and $this->youtube_id) {
            $btnHref = 'https://www.youtube.com/watch?v=' . $this->youtube_id;
            $btnText = 'Смотреть видео';
        }

        return '<p>
                <a href="' . $btnHref . '" class="tdno">
                    <div class="bybtn">
                        <span class="bybtntxt">' . $btnText . '</span>
                    </div>
                </a>
			</p>';
    }

    public static function byCache(int $id, bool $tryReCache = true): bool|self
    {
        $qwe = qwe("
            SELECT cache FROM anonces 
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

        /** @var Announce $Announce */
        $Announce = JsonDecoder::cloneFromAny(json_decode($Json), self::class);
        return $Announce;
    }

}