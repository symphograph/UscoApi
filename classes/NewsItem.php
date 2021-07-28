<?php


class NewsItem
{


    public function __construct(
        public int $id = 0,
        public string $tit = 'Заголовок новости',
        public string $descr = 'Краткое описание',
        public string $content = 'Текст новости',
        public string $img = 'img/news/default_news_img.svg',
        public string $ver = '892648eb37542d469424b3448268d452',
        public string $date = '01.01.1970',
        public int $show = 0,
        public int $evid = 0,
        public string $link = ''
    )
    {
    }

    function byId(int $new_id) : bool
    {
        $qwe = qwe("
        SELECT * from `news`
        WHERE `new_id` = '$new_id'
        ");
        if(!$qwe or !$qwe->rowCount())
            return false;
        $q = $qwe->fetchObject();

        return self::byQ($q) ;
    }

    function byQ(object $q)
    {
        $this->id = $q->new_id;
        $this->tit = $q->new_tit ?? '';
        $this->descr = $q->descr ?? '';
        $this->content = $q->content ?? '';
        $this->img = $q->img ?? '';
        if(empty($this->img))
            $this->img = 'img/news/default_news_img.svg';
        if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $this->img))
            $this->img = $q->img = 'img/news/default_news_img.svg';
        $this->ver = '?ver=' . md5_file($_SERVER['DOCUMENT_ROOT'] . '/' . $this->img);
        $this->date = $q->date;
        $this->show = $q->show;
        $this->evid = $q->evid;


        if($this->evid){
            $this->link = 'event.php?evid=' . $this->evid;
        }else{
            $this->link = 'new.php?new_id=' . $this->id;
        }



        return true;
    }

    function PrintItem()
    {
        ?>

        <div class="narea">
        <div class="nimg_block">
            <div>
                <a href="<?php echo $this->link;?>">
                    <img src="<?php echo $this->img, $this->ver?>" width="260px" alt="Изображение не найдено"/>
                </a>
            </div>
        </div>


        <div class="tcol">
            <div class="ntitle">
                <a href="<?php echo $this->link;?>">
                    <b><?php echo $this->tit;?></b>
                </a>
            </div>
            <br>
            <a href="<?php echo $this->link;?>">
                <?php echo $this->descr;?>
            </a>
            <br><br>
            <?php
            $ndate = strtotime($this->date);
            $ndate = ru_date('',$ndate);
            ?>
            <span class="ndate"><?php echo $ndate;?></span>
            <br>
        </div>
        </div><?php
    }

    function PajeItem()
    {
        $img = '<img src="'.$this->img.$this->ver.'" width="320px"/>';
       ?>
        <div class="newsarea">
            <div class="ntitle"><?php echo $this->tit;?></div>
            <hr>
            <div class="narea">

                <?php
                $file = dirname($_SERVER['DOCUMENT_ROOT']).'/includs/news/new_'.$this->id.'.php';
                if(file_exists($file))
                {
                    include_once $file;

                }elseif(!$this->content)
                {
                    echo '<div class="text">' . $this->descr . '</div>';
                }else
                {
                    echo '<div class="text">' . $this->content . '</div>';
                }
                ?>
            </div>
        </div>
        <?php
    }
}