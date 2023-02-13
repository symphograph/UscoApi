<?php
namespace App;

use JetBrains\PhpStorm\Pure;
use PDO;
use PDOStatement;
use Symphograph\Bicycle\JsonDecoder;

class Anonce
{
    public int|null    $ev_id       = 0;
    public int|null    $concert_id  = 0;
    public int|null    $hall_id     = 8;
    public string|null $prog_name   = 'Название';
    public string|null $sdescr      = '';
    public string|null $description = 'Описание';
    public string|null $img         = '';
    public string|null $topimg      = 'deftop3.jpg';
    public string|null $datetime    = '';
    public int|null    $pay         = 0;
    public int         $age         = 0;
    public string|null $ticket_link = '';
    public string|null $hall_name   = '';
    public string|null $youtube_id  = '';
    public bool        $complited   = false;
    public Hall|null        $Hall;
    public Img|Poster  $Poster;
    public Img|Poster  $TopPoster;
    public string|null $map         = '';
    public string|null $topImgUrl   = '';
    public string|bool $error       = false;
    public int|bool    $show        = false;
    public string      $verLink     = '';
    public string      $date        = '';
    public string      $time        = '';
    public string|null $prrow       = '';
    public string|null $dateFormated = '';
    private string|null $cache;

    const PAYS = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Продажа завершена'];

    public static function addNewAnonce(): bool|self
    {
        $id = self::createNewID();
        if(!$id)
            return false;

        $Anonce = Anonce::byId(1);
        if(!$Anonce)
            return false;

        $Anonce->ev_id = $id;
        if(!$Anonce->putToDB())
            return false;

        return $Anonce;
    }

    private static function createNewID() : int|bool
    {
        $qwe = qwe("SELECT max(concert_id)+1 as id FROM anonces");
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        $q = $qwe->fetchObject();
        return $q->id;
    }

    public function __set(string $name, $value): void
    {

    }

    #[Pure] public static function clone(self $q) : self
    {
        $Anonce = new Anonce();
        foreach ($q as $k=>$v){
            $Anonce->$k = $v;
        }
        return $Anonce;
    }

    private static function checkClass(self $Object) : self
    {
        return $Object;
    }

    public static function byQ(self $q) : Anonce|bool
    {
        $Anonce = self::checkClass($q);

        $Anonce->Hall = new Hall(
            id:   $Anonce->hall_id,
            name: $Anonce->hall_name,
            map:  $Anonce->map
        );

        $Anonce->Poster = Poster::byAnonceId($Anonce->ev_id);
        $Anonce->TopPoster = Poster::byAnonceId($Anonce->ev_id,true);
        if(empty($Anonce->datetime)){
            $Anonce->datetime = date('Y-m-d H:i',time() + 3600*24);
        }
        $Anonce->complited = (strtotime($Anonce->datetime) < (time()+3600*8));
        $Anonce->datetime = date('Y-m-d H:i',strtotime($Anonce->datetime));
        $Anonce->date = date('Y-m-d',strtotime($Anonce->datetime));
        $Anonce->show = boolval($Anonce->show);
        $Anonce->getTopImgUrl();
        $Anonce->prrow = self::PAYS[$Anonce->pay] ?? '';
        if($Anonce->pay == 3 and $Anonce->complited){
            $Anonce->prrow = 'Продажа завершена';
        }

        $Anonce->dateFormated = $Anonce->EvdateFormated();

        return $Anonce;

    }

    public static function byId(int $ev_id) : bool|self
    {

        $qwe = qwe("
            SELECT
            a.*,
            a.concert_id as ev_id,
            h.hall_name,
            h.map
            FROM
            anonces as a
            INNER JOIN halls as h ON a.hall_id = h.hall_id
            WHERE a.concert_id = :ev_id
            ",['ev_id'=>$ev_id]);

        if(!$qwe or !$qwe->rowCount())
            return false;

        $q = $qwe->fetchObject(get_class());
        return Anonce::byQ($q);
    }

    private function EvdateFormated(): string
    {
        $evdate = strtotime($this->datetime);

        if(date('Y',$evdate) == date('Y',time())){
            $evdateru = ru_date('d %bg',$evdate);
            $evtime = date('H:i',$evdate);
            return $evdateru.' в '.$evtime;
        }
        else
            return date('d.m.Y в H:i',$evdate);
    }

    private function fDate() : string
    {
        return date('d.m.Y',strtotime($this->datetime));
    }

    private function fTime() : string
    {
        return date('H:i',strtotime($this->datetime));
    }

    public function getProgNameClean() : string
    {
        $progName = str_replace('<br>',' ',$this->prog_name);
        $progName = strip_tags($progName);
        return preg_replace('/^ +| +$|( ) +/m', '$1', $progName);
    }

    private static function collectionParams(int $sort, int $year = 0, bool $new = false) : array
    {
        if(!$year)
            $year = date('Y');

        $sorts = ['anonces.datetime DESC', 'anonces.datetime ASC'];
        $sort = $sorts[$sort] ?? 'anonces.datetime DESC';
        $curDate = '2000-01-01 00:00';
        if($new){
            $curDate = date('Y-m-d H:i', time() + 3600 * 8);
        }
        return [
            'sort'    => $sort,
            'year'    => $year,
            'curDate' => $curDate
        ];
    }

    public static function getCollection(int $sort, int $year = 0, bool $new = false) : array|bool
    {
        $params = self::collectionParams($sort, $year, $new);
        $qwe = qwe("
        SELECT    
        anonces.concert_id as ev_id,
        anonces.hall_id,
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
        halls.hall_name,
        halls.map,
        video.youtube_id
        FROM
        anonces
        INNER JOIN halls ON anonces.hall_id = halls.hall_id
            AND anonces.datetime >= :curDate
        LEFT JOIN video ON anonces.concert_id = video.concert_id
        WHERE year(datetime) = :year
        ORDER BY ".$params['sort'], [
                'curDate' => $params['curDate'],
                'year'    => $params['year']
            ]
        );

        if(!$qwe or !$qwe->rowCount()){
            return false;
        }

        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS,"Anonce");
        //printr($qwe);
        $arr = [];
        foreach ($qwe as $q){
            $Anonce = Anonce::byQ($q);
            if(!$Anonce) continue;
            $arr[] = $Anonce;
        }


        return $arr;
    }

    public static function getCollectionByCache(int $sort, int $year = 0, bool $new = false): array
    {
        $params = self::collectionParams($sort, $year, $new);
        $qwe = qwe("
            SELECT concert_id ev_id, datetime, cache from anonces
            WHERE year(datetime) = :year
            AND anonces.datetime >= :curDate
            ORDER BY " . $params['sort'],
            [
                'year'    => $params['year'],
                'curDate' => $params['curDate']
            ]
        );
        if(!$qwe || !$qwe->rowCount())
            return [];

        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS,get_class());

        /** @var array<self> $arr */
        $arr = [];

        foreach ($qwe as $Anonce){
            $Anonce = self::checkClass($Anonce);
            if(!empty($Anonce->cache)){
                $arr[] = self::decodeFromJson($Anonce->cache);
                continue;
            }

            if(!Anonce::reCache($Anonce->ev_id)){
                continue;
            }

            $recachedAnonce = Anonce::byCache($Anonce->ev_id);
            if(!$recachedAnonce) {
                continue;
            }

            $arr[] = $recachedAnonce;
        }

        if(count($arr) === count($qwe)){
            return $arr;
        }
        return [];
    }

    public static function apiValidation() : array|bool
    {
        return [
            'year' => intval($_POST['year'] ?? date('Y')),
            'sort' => intval($_POST['sort'] ?? 0),
            'new'  => boolval($_POST['new'] ?? false)
        ];
    }

    private function getTopImgUrl(): void
    {
        $url = Poster::getSrc($this->ev_id,1);
        if($url){
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

    public static function setByPost() : self|bool
    {
        if(empty($_POST['evdata']))
            return false;

        $data = (object) $_POST['evdata'];

        $Anonce = new self();
        foreach ($Anonce as $k => $v){
            if(!empty($data->$k)){
                $Anonce->$k = $data->$k;
            }
        }
        return $Anonce;
    }

    public function putToDB() : bool
    {
        $qwe = qwe(
            "REPLACE INTO anonces (
                      concert_id, 
                      hall_id, 
                      prog_name, 
                      sdescr, 
                      description, 
                      topimg, 
                      datetime, 
                      pay, 
                      age, 
                      ticket_link,
                      `show`
                      ) 
                      VALUES (
                      :concert_id, 
                      :hall_id, 
                      :prog_name, 
                      :sdescr, 
                      :description,
                      :topimg, 
                      :datetime, 
                      :pay, 
                      :age, 
                      :ticket_link,
                      :show
                      )",
                    [
                        'concert_id'  => $this->ev_id,
                        'hall_id'     => $this->hall_id,
                        'prog_name'   => $this->prog_name,
                        'sdescr'      => $this->sdescr,
                        'description' => $this->description,
                        'topimg'      => $this->topimg,
                        'datetime'    => $this->datetime,
                        'pay'         => $this->pay,
                        'age'         => $this->age,
                        'ticket_link' => $this->ticket_link,
                        'show'        => intval($this->show)
                    ]
        );

        return $qwe && self::reCache($this->ev_id);
    }

    public static function delete(int $id): bool|PDOStatement
    {
        return qwe("DELETE FROM anonces WHERE concert_id = :id AND concert_id != 1",['id'=>$id]);
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
                    if(strtotime($this->datetime) > strtotime('2020-03-10')) {
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
                                url: "<?php echo 'https://' . $_SERVER['SERVER_NAME'] . '/event.php?evid=' . $this->ev_id?>",
                                image: "<?php echo 'https://' . $_SERVER['SERVER_NAME'] . $this->Poster->verLink?>",
                                noparse: true
                            },{ type: "round", text: "Поделиться"}));
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    public static function getReady(int $id): bool|Anonce
    {
        $Anonce = Anonce::byId($id);
        if(!$Anonce) return false;
        $Anonce->verLink = 'https://'. $_SERVER['SERVER_NAME'] .'/'.$Anonce->Poster->verLink;
        $Anonce->prog_name = strip_tags($Anonce->prog_name);
        $Anonce->getTopImgUrl();
        $Anonce->date = date('Y-m-d', strtotime($Anonce->datetime));
        $Anonce->time = date('H:i', strtotime($Anonce->datetime));
        $Anonce->initVideo();


        return $Anonce;
    }

    private function initVideo(): void
    {
        $qwe = qwe("select youtube_id from video where concert_id = :id",['id'=>$this->ev_id]);
        if(!$qwe || !$qwe->rowCount()){
            return;
        }
        $qwe = $qwe->fetchObject();
        $this->youtube_id = $qwe->youtube_id ?? '';
    }

    public function saveCache(): bool|PDOStatement
    {
        $Anonce = $this;
        if(isset($Anonce->cache))
            unset($Anonce->cache);

        return qwe("
                    UPDATE anonces 
                    SET cache = :cache 
                    WHERE concert_id = :id", [
                        'cache' => json_encode($this,JSON_HEX_QUOT),
                        'id'    => $this->ev_id
                        ]
                );
    }

    public static function reCache(int $id) : bool
    {
        $Anonce = Anonce::getReady($id);
        if(!$Anonce){
            return false;
        }

        return boolval($Anonce->saveCache());

    }

    private function evDate()
    {
        ?>
        <div class="evdate" <?php if(!$this->complited) echo 'style="box-shadow:  0 1px 16px 0px #00ff09a8"'?>>
            <?php echo self::EvdateFormated()?>
        </div>
        <?php
    }

    private function byeButton(): string
    {
        $btnHref = 'event.php?evid=' . $this->ev_id;
        $btnText = 'Подробно';

        if($this->pay == 3 and !$this->complited) {
            $btnHref = $this->ticket_link;
            $btnText = 'Купить онлайн';
        }

        if($this->complited and $this->youtube_id){
            $btnHref = 'https://www.youtube.com/watch?v=' . $this->youtube_id;
            $btnText = 'Смотреть видео';
        }

        return '<p>
                <a href="'.$btnHref.'" class="tdno">
                    <div class="bybtn">
                        <span class="bybtntxt">'.$btnText.'</span>
                    </div>
                </a>
			</p>';
    }

    public static function byCache(int $id, bool $tryReCache = true): bool|self
    {
        $qwe = qwe("
            SELECT cache FROM anonces 
            WHERE concert_id = :id",
            compact('id')
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        $cache = $qwe->fetchObject()->cache;
        if(!empty($cache)){
            return self::decodeFromJson($cache);
        }

        if($tryReCache){
            Anonce::reCache($id);
            return Anonce::byCache($id,false);
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

        /** @var Anonce $Anonce */
        $Anonce = JsonDecoder::cloneFromAny(json_decode($Json), self::class);
        return $Anonce;
    }

}