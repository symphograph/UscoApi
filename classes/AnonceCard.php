<?php


class AnonceCard extends Anonce
{
    public string|null $prrow = '';
    public string|null $topImgUrl;
    public string|null $dateFormated;

    public function clone(Anonce $q) : bool
    {
        if(!parent::clone($q)){
            return false;
        }

        $this->prrow = self::PAYS[$this->pay] ?? '';
        if($this->pay == 3 and $this->complited){
            $this->prrow = 'Продажа завершена';
        }
        self::getTopImgUrl();
        $this->dateFormated = self::EvdateFormated();
        return true;
    }

    private function getTopImgUrl(){
        $file = 'img/afisha/'.$this->topimg;
        if(!file_exists($file)){
            $file = 'img/afisha/deftop3.jpg';
        }
        $img = new Img('img/afisha/'.$this->topimg);
        $this->topImgUrl = $img->verLink;
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

    public function evDate()
    {
        ?>
        <div class="evdate" <?php if(!$this->complited) echo 'style="box-shadow:  0 1px 16px 0px #00ff09a8"'?>>
            <?php echo parent::EvdateFormated()?>
        </div>
        <?php
    }

    function byeButton(): string
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