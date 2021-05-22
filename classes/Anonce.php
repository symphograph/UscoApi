<?php


class Anonce
{
    public int|null $ev_id = 0;
    public int|null $hall_id = 8;
    public string|null $prog_name = 'Название';
    public string|null $sdescr = '';
    public string|null $description;
    public string|null $topimg;
    public string|null $aftitle;
    public string|null $datetime;
    public int|null $pay = 0;
    public int|null $age = null;
    public string|null $ticket_link = '';
    public string|null $hall_name = '';
    public string|null $youtube_id = '';
    public bool $complited = false;
    const PAYS = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];


    public function clone(object|array $q)
    {
        $q = (object) $q;
        foreach ($q as $k=>$v){
            if(!$v or empty($v))
                continue;
            $this->$k = $v;
        }
        $this->complited = (strtotime($this->datetime) < (time()+3600*8));
        return true;
    }

    public function byId(int $ev_id){
        $qwe = qwe("
            SELECT
            a.*,
            a.concert_id as ev_id,
            h.hall_name,
            h.map
            FROM
            anonces as a
            INNER JOIN halls as h ON a.hall_id = h.hall_id
            WHERE a.concert_id = '$ev_id'
            ");

        if(!$qwe or !$qwe->num_rows)
            return false;

        $q = mysqli_fetch_object($qwe);

        return self::clone($q);
    }

    public function EvdateFormated()
    {
        $evdate = strtotime($this->datetime);

        if(date('Y',$evdate) == date('Y',time())){
            $evdateru = ru_date('%e&nbsp;%bg&nbsp',$evdate);
            $evtime = date('H:i',$evdate);
            return $evdateru.' в '.$evtime;
        }
        else
            return date('d.m.Y в H:i',$evdate);
    }
}