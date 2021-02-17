<?php


class Anonce
{
    public int $ev_id = 0;
    public int $hall_id = 8;
    public string $prog_name = 'Название';
    public string $sdescr = '';
    public string $description;
    public string $img;
    public string $topimg;
    public string $aftitle;
    public string $datetime;
    public int $pay = 0;
    public int $age;
    public $ticket_link = '';
    public $hall_name = '';
    public $payName = 'Условия входа';
    const PAYS = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];

    function byQ(object $q) : bool
    {
        $this->ev_id = $q->ev_id ?? 0;
        $this->hall_id = $q->hall_id;
        $this->prog_name = $q->prog_name;
        $this->sdescr = $q->sdescr;
        $this->description = $q->description;
        $this->img = $q->img;
        $this->topimg = $q->topimg;
        $this->aftitle = $q->aftitle;
        $this->datetime = $q->datetime;
        $this->pay = $q->pay;
        $this->age = $q->age;
        $this->ticket_link = $q->ticket_link;
        $this->hall_name = $q->hall_name;
    }

    function getdb(int $ev_id){
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

        return self::byQ($q);
    }

}