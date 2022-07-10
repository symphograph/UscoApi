<?php


use JetBrains\PhpStorm\Pure;

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
    public Hall        $Hall;
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

    const PAYS = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];

    public static function addNewAnonce(): bool|Anonce
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

    #[Pure] public static function clone(Anonce $q) : Anonce
    {
        $Anonce = new Anonce();
        foreach ($q as $k=>$v){
            $Anonce->$k = $v;
        }
        return $Anonce;
    }

    public static function byQ(Anonce $q) : Anonce|bool
    {
        $Anonce = new Anonce();
        foreach ($q as $k=>$v){
            if(!$v or empty($v))
                continue;
            $Anonce->$k = $v;
        }

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

    public static function byId(int $ev_id) : bool|Anonce
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

        $q = $qwe->fetchAll(PDO::FETCH_CLASS,"Anonce");
        return Anonce::byQ($q[0]);
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
            $curDate = date('Y-m-d H:i');
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
            SELECT concert_id id, cache from anonces
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

        $arr = [];

        foreach ($qwe as $q){
            if(!empty($q['cache'])){
                $arr[] = json_decode($q['cache']);
                continue;
            }

            if(!Anonce::reCache($q['id'])){
                continue;
            }

            $recachedAnonce = Anonce::byCache($q['id']);
            if(!$recachedAnonce) {
                continue;
            }

            $arr[] = json_decode($recachedAnonce);
        }

        if(count($arr) === $qwe->rowCount()){
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

    public static function setByPost() : Anonce|bool
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
        //$Anonce->show = intval($data);
        $Anonce->Hall = new Hall($data->Hall['id'],$data->Hall['name'], $data->Hall['map']);
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
                        'hall_id'     => $this->Hall->id,
                        'prog_name'   => $this->prog_name,
                        'sdescr'      => $this->sdescr,
                        'description' => $this->description,
                        'topimg'      => $this->topimg,
                        'datetime'    => $this->datetime,
                        'pay'         => $this->pay,
                        'age'         => $this->age,
                        'ticket_link' => $this->ticket_link,
                        'show'        => $this->show
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
        //$data = json_encode(['data' => $Anonce]);
        return $Anonce;
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

    public static function reCacheAll(): bool
    {
        qwe("UPDATE anonces 
                    SET cache = null");

        $qwe = qwe("SELECT concert_id FROM anonces");
        if(!$qwe || !$qwe->rowCount()){
            return false;
        }
        foreach($qwe as $q){
            $Anonce = Anonce::getReady($q['concert_id']);
            if(!$Anonce) continue;
            $Anonce->saveCache();
        }
        return true;
    }

    public static function reCache(int $id) : bool
    {
        $Anonce = Anonce::getReady($id);
        if(!$Anonce){
            return false;
        }

        return boolval($Anonce->saveCache());

    }

    public function printCard()
    {
        global $myip;
        if((!$myip) and $this->ev_id < 4) return false;
        ?>
        <div class="eventbox tdno">
            <div class="pressme" >
                <div>
                    <div class="affot">
                        <img src="<?php echo $this->topImgUrl ?>" width="100%" height="auto">

                        <?php
                        if($this->age) {
                            ?><div class="age"><?php echo $this->age?>+</div><?php
                        }
                        ?>
                    </div>
                    <br>
                    <?php self::evDate(); ?>
                    <a href="<?php echo $this->Hall->map;?>" class="hall_href" target="_blank"><?php echo $this->Hall->name;?></a>
                </div>


                <div class="aftext">
                    <a href="event.php?evid=<?php echo $this->ev_id;?>" class="tdno">
                        <div class="evname"><?php echo $this->prog_name;?></div>
                        <br>
                        <div class="sdescr"><?php echo $this->sdescr?>
                            <br><br>
                            Художественный руководитель  и главный дирижер - <b>Тигран Ахназарян</b>.
                        </div>
                    </a>

                </div>
                <div class="downbox">
                    <div class="tdno">
                        <div><br>
                            <p><span><?php echo $this->prrow?></span></p>
                            <br>
                            <?php echo self::byeButton();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
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

    public static function byCache(int $id, bool $tryReCache = true): bool|string
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
            return $cache;
        }

        if($tryReCache){
            Anonce::reCache($id);
            return Anonce::byCache($id,false);
        }
        return false;
    }

}