<?php


class AnonceCard extends Anonce
{
    public string|null $prrow = '';
    public string|null $img;
    public string|null $map;

    public function clone(object|array $q)
    {
        if(!parent::clone($q)){
            return false;
        }

        $this->prrow = self::PAYS[$this->pay] ?? '';
        if($this->pay == 3 and $this->complited){
            $this->prrow = 'Продажа завершена';
        }
        return true;
    }

    public function printItem()
    {
        global $myip;
        if((!$myip) and $this->ev_id < 4) return false;
        ?>
        <div class="eventbox tdno">
            <div class="pressme" >
                <div>
                    <div class="affot">
                        <img src="<?php echo 'img/afisha/'.$this->topimg;?>?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/img/afisha/'.$this->topimg)?>" width="100%" height="auto">

                        <?php
                        if($this->age) {
                            ?><div class="age"><?php echo $this->age?>+</div><?php
                        }
                        ?>
                    </div>
                    <br>
                    <?php self::evDate(); ?>
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

    public function evDate()
    {
        ?>
        <div class="evdate" <?php if(!$this->complited) echo 'style="box-shadow:  0 1px 16px 0px #00ff09a8"'?>>
            <?php echo parent::EvdateFormated()?>
        </div>
        <?php
    }

    function byeButton()
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

}