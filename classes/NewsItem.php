<?php


class NewsItem
{

    public Img $pwImg;

    public function __construct(
        public int    $id = 0,
        public string $title = 'Заголовок новости',
        public string $descr = 'Краткое описание',
        public string $content = 'Текст новости',
        public string $img = 'img/news/default_news_img.svg',
        public string $ver = '892648eb37542d469424b3448268d452',
        public string $date = '01.01.1970',
        public int    $show = 0,
        public int    $evid = 0,
        public string $link = '',
        public string $refName = '',
        public string $refLink = '',
    )
    {
        if($id)
            return self::byId($id);
        return false;
    }

    function byId(int $id) : bool
    {
        $qwe = qwe("
        SELECT * from `news`
        WHERE `id` = :id
        ",['id'=>$id]);
        if(!$qwe or !$qwe->rowCount())
            return false;
        $q = $qwe->fetchObject();

        return self::byQ($q) ;
    }

    function byQ(object $q)
    {
        $this->id    = $q->id;
        $this->title = $q->title ?? '';
        $this->descr = $q->descr ?? '';
        $this->content = $q->content ?? '';
        $this->img = $q->img ?? '';
        //if(empty($this->img))
        //    $this->img = 'img/news/default_news_img.svg';
        //if(!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $this->img))
        //    $this->img = $q->img = 'img/news/default_news_img.svg';
        //$this->ver = '?ver=' . md5_file($_SERVER['DOCUMENT_ROOT'] . '/' . $this->img);
        $this->date = $q->date;
        $this->show = $q->show;
        $this->evid = $q->evid;
        $this->refName = $q->refName ?? '';
        $this->refLink = $q->refLink ?? '';


        if($this->evid){
            $this->link = 'event.php?evid=' . $this->evid;
        }else{
            $this->link = 'new.php?new_id=' . $this->id;
        }



        return true;
    }

    private function resizePw(string $file, int $size, string $extension)
    {
        $newFile = 'img/news/pw/' . $this->id . '.' . $extension;
        if(file_exists($_SERVER['DOCUMENT_ROOT']. '/'. $newFile)){
            $this->pwImg = new Img($newFile);
            return $this->pwImg->exist;
        }
        $image = new Imagick($_SERVER['DOCUMENT_ROOT']. '/' .$file);
        $image->thumbnailImage(260,162);

        try {
            $image->writeImage($_SERVER['DOCUMENT_ROOT']. '/'. $newFile);
        } catch (ImagickException $e) {
            return false;
        }

        $this->pwImg = new Img($newFile);
        return $this->pwImg->exist;

    }

    private function isThumbnailCached() : bool
    {
        $file = $_SERVER['DOCUMENT_ROOT'].'/' . $this->img;
        $ext = pathinfo($file,PATHINFO_EXTENSION);
        $cachedFile = 'img/news/pw/' . $this->id . $ext;
        if(file_exists($cachedFile) && !is_dir($cachedFile)){
            $this->pwImg = new Img($cachedFile);
            return true;
        }
        $this->pwImg = new Img($this->img);
        return false;
    }

    function PrintItem()
    {
        if(!self::isThumbnailCached()){
            if(!$this->pwImg->exist){
                $this->pwImg = new Img('img/news/default_news_img.svg');
            }

            if($this->pwImg->extension != 'svg'){
                self::resizePw($this->pwImg->file, 260, $this->pwImg->extension);

            }
        }
        ?>

        <div class="narea">
        <div class="nimg_block">
            <div>
                <a href="<?php echo $this->link;?>">
                    <img src="<?php echo $this->pwImg->verLink?>" width="260px" alt="Изображение не найдено"/>
                </a>
            </div>
        </div>


        <div class="tcol">
            <div class="ntitle">
                <a href="<?php echo $this->link;?>">
                    <b><?php echo $this->title;?></b>
                </a>
            </div>
            <br>
            <a href="<?php echo $this->link;?>">
                <?php echo $this->descr;?>
            </a>
            <br><br>
            <span class="ndate"><?php echo self::dateFormated()?></span>
            <br>
        </div>
        </div><?php
    }

    private function dateFormated() : string
    {
        return ru_date('',strtotime($this->date));
    }

    function PajeItem() : string
    {
        ob_start();
       ?>
        <div class="newsarea">
            <div class="ntitle"><?php echo $this->title;?></div>
            <hr>

            <div class="narea">

                <?php
                $file = dirname($_SERVER['DOCUMENT_ROOT']).'/includs/news/new_'.$this->id.'.php';
                if(file_exists($file))
                {
                    $img = (new Img($this->img))->tagArticle();
                    include_once $file;

                }elseif(!$this->content)
                {
                    echo '<div class="text">' . $this->descr . '</div>';
                }else
                {
                    echo '<div class="text">' . $this->content . '</div>';
                }

                if(!empty($this->refLink && !empty($this->refName))){
                    ?><br><br>
                    <div>
                    <b>Источник: </b>
                    <a href="<?php echo $this->refLink?>"><?php echo $this->refName?></a>
                    </div>
                    <?php
                }
                ?>


            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}