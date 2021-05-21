<?php


class Anonce
{
    public int|null $ev_id = 0;
    public int|null $hall_id = 8;
    public string|null $prog_name = 'Название';
    public string|null $sdescr = '';
    public string|null $description;
    public string|null $img;
    public string|null $map;
    public string|null $topimg;
    public string|null $aftitle;
    public string|null $datetime;
    public int|null $pay = 0;
    public int|null $age = null;
    public string|null $ticket_link = '';
    public string|null $hall_name = '';
    public string|null $youtube_id = '';
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

    public function clone($q)
    {
        $q = (object) $q;
        foreach ($q as $k=>$v){
            if(!$v or empty($v))
                continue;
            $this->$k = $v;
        }
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

    public function printItem()
    {

        $complited = (strtotime($this->datetime) < (time()+3600*8));
        global $myip;
        if((!$myip) and $this->ev_id < 4) return false;


        $prrow = self::PAYS[$this->pay] ?? '';


        $byebtn = byeButton($prrow,'event.php?evid='.$this->ev_id,'Подробно');

        if($this->pay == 5 and !$complited) {
            $byebtn = byeButton($prrow,$this->ticket_link,'Купить онлайн');
        }

        if($_SERVER['SCRIPT_NAME'] == '/posters.php' and $this->youtube_id){
            $byebtn = byeButton($prrow,'https://www.youtube.com/watch?v='.$this->youtube_id,'Смотреть видео');
        }

        ?><div class="eventbox tdno">

        <div class="pressme">
            <div>
                <div class="affot">
                    <img src="<?php echo 'img/afisha/'.$this->topimg;?>?ver=<?php echo md5_file('img/afisha/'.$this->topimg)?>" width="100%" height="auto">

                    <?php
                    if($this->age)
                    {
                        ?><div class="age"><?php echo $this->age?>+</div><?php
                    }
                    ?>

                </div>
                <br>
                <div class="evdate">
                    <?php echo EvdateFormated($this->datetime)?>
                </div>
                <a href="<?php echo $this->map;?>" class="hall_href" target="_blank"><?php echo $this->hall_name;?></a>
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
                <div class="tdno"><?php echo $byebtn;?></div>
            </div>

        </div>
        </div>
        <?php
    }

}