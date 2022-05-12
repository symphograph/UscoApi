<?php


class Anonce
{
    public int|null    $ev_id       = 0;
    public int|null    $concert_id       = 0;
    public int|null    $hall_id     = 8;
    public string|null $prog_name   = 'Название';
    public string|null $sdescr      = '';
    public string|null $description = 'Описание';
    public string|null $img = '';
    public string|null $topimg = 'deftop3.jpg';
    public string|null $datetime = '';
    public int|null    $pay         = 0;
    public int    $age         = 0;
    public string|null $ticket_link = '';
    public string|null $hall_name   = '';
    public string|null $youtube_id  = '';
    public bool        $complited   = false;
    public Hall        $Hall;
    public Img|Poster $Poster;
    public string|null $map = '';
    public string|null $topImgUrl = '';
    public string|bool $error = false;
    public int|bool $show = false;

    const PAYS = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];

    public static function addNewAnonce(): bool|Anonce
    {
        $id = self::createNewID();
        if(!$id)
            return false;

        $Anonce = new Anonce();
        if(!$Anonce->byId(1))
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

    public function clone(Anonce $q): bool
    {
        foreach ($q as $k=>$v){
            if(!$v or empty($v))
                continue;
            $this->$k = $v;
        }

        $this->Hall = new Hall(
            id:   $this->hall_id,
            name: $this->hall_name,
            map:  $this->map
        );
        $this->Poster = Poster::byAnonceId($this->ev_id);
        if(empty($this->datetime)){
            $this->datetime = date('Y-m-d H:i',time() + 3600*24);
        }
        $this->complited = (strtotime($this->datetime) < (time()+3600*8));
        $this->datetime = date('Y-m-d H:i',strtotime($this->datetime));
        $this->date = date('Y-m-d',strtotime($this->datetime));
        $this->show = boolval($this->show);

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

    public static function getCollection(int $sort, int $year = 0, bool $new = false) : array|bool
    {
        if(!$year){
            $year = date('Y');
        }

        $sorts = ['anonces.datetime DESC', 'anonces.datetime ASC'];
        $curDate = '2000-01-01 00:00';
        if($new){
            $curDate = date('Y-m-d H:i');
        }
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
            AND anonces.datetime >= :curdate
            /*AND anonces.`show` = 1*/
        LEFT JOIN video ON anonces.concert_id = video.concert_id
        WHERE year(datetime) = :year
        ORDER BY ".$sorts[$sort],['year'=>$year,'curdate'=> $curDate]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }

        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS,"Anonce");
        //printr($qwe);
        $arr = [];
        foreach ($qwe as $q){
            $Anonce = new AnonceCard();
            $Anonce->clone($q);
            $Anonce->getTopImgUrl();
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

        $sort = $_POST['sort'] ?? 0;
        $sort = intval($sort);

        $new = $_POST['new'] ?? 0;
        $new = boolval($new);

        return [
            'year' => $year,
            'sort' => $sort,
            'new' => $new
        ];
    }

    protected function getTopImgUrl(){
        $url = Poster::getSrc($this->ev_id,1);
        if($url){
            $this->topImgUrl = $url;
            return true;
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
        $data = $_POST ?? json_decode(file_get_contents('php://input'), true)['params'] ?? null;
        if(empty($data['evdata']))
            return false;

        $data = (object) $data['evdata'];

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
        return boolval($qwe);
    }

    public static function delete(int $id): bool|PDOStatement
    {
        return qwe("DELETE FROM anonces WHERE concert_id = :id",['id'=>$id]);
    }

}