<?php


class Anonce
{
    public int|null    $ev_id       = 0;
    public int|null    $hall_id     = 8;
    public string|null $prog_name   = 'Название';
    public string|null $sdescr      = '';
    public string|null $description = 'Описание';
    public string|null $img;
    public string|null $topimg = 'deftop3.jpg';
    public string|null $aftitle;
    public string|null $datetime;
    public int|null    $pay         = 0;
    public int|null    $age         = null;
    public string|null $ticket_link = '';
    public string|null $hall_name   = '';
    public string|null $youtube_id  = '';
    public bool        $complited   = false;
    public Hall        $Hall;
    public string|null $map;
    const PAYS = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];

    public function __set(string $name, $value): void
    {

    }

    public function clone(Anonce $q): bool
    {
        foreach ($q as $k=>$v){
            if(!$v or empty($v))
                continue;
            $this->$k = $v;
        }
        $this->complited = (strtotime($this->datetime) < (time()+3600*8));
        $this->Hall = new Hall(
            id:   $this->hall_id,
            name: $this->hall_name,
            map:  $this->map
        );
        return true;
    }

    public function byId(int $ev_id) : bool
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

        return self::clone($q[0]);
    }

    public function EvdateFormated(): string
    {
        $evdate = strtotime($this->datetime);

        if(date('Y',$evdate) == date('Y',time())){
            $evdateru = ru_date('%e %bg',$evdate);
            $evtime = date('H:i',$evdate);
            return $evdateru.' в '.$evtime;
        }
        else
            return date('d.m.Y в H:i',$evdate);
    }

    public function fDate() : string
    {
        return date('d.m.Y',strtotime($this->datetime));
    }

    public function fTime() : string
    {
        return date('H:i',strtotime($this->datetime));
    }

    public function getProgNameClean() : string
    {
        $progName = str_replace('<br>',' ',$this->prog_name);
        $progName = strip_tags($progName);
        return preg_replace('/^ +| +$|( ) +/m', '$1', $progName);
    }

    public static function getCollection(int $sort, int $year = 0) : array|bool
    {
        if(!$year){
            $year = date('Y');
        }
        $sorts = ['DESC', ''];
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
        halls.hall_name,
        halls.map,
        video.youtube_id
        FROM
        anonces
        INNER JOIN halls ON anonces.hall_id = halls.hall_id
        LEFT JOIN video ON anonces.concert_id = video.concert_id
        WHERE year(datetime) = :year
        ORDER BY anonces.datetime ".$sorts[$sort],['year'=>$year]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }

        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS,"Anonce");
        $arr = [];
        foreach ($qwe as $q){
            $Anonce = new AnonceCard();
            $Anonce->clone($q);
            //printr($Anonce);
            $arr[] = $Anonce;
        }

        return $arr;
    }

    public static function apiValidation() : array|bool
    {
        if(empty($_POST)){
            return false;
        }

        $year = $_POST['year'] ?? date('Y');
        $year = intval($year);

        $sort = $_POST['sort']['value'] ?? 0;
        $sort = intval($sort);
        return [
            'year' => $year,
            'sort' => $sort
        ];
    }
}