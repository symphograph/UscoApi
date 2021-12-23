<?php


class NewsItem
{
    public int         $id      = 0;
    public string|null $title   = 'Заголовок новости';
    public string|null $descr   = 'Краткое описание';
    public string|null $content = 'Текст новости';
    public string|null $img     = 'img/news/default_news_img.svg';
    /*public string $ver = '892648eb37542d469424b3448268d452';*/
    public string|null       $date    = '01.01.1970';
    public int               $show    = 0;
    public int               $evid    = 0;
    public string|null       $link    = '';
    public string|null       $refName = '';
    public string|null       $refLink = '';
    public Img               $pwImg;
    public NewsImgCollection $imgCollection;
    public string|null $cache;

    public function __construct(int $id = 0)
    {
        if(!$id)
            return false;

        self::byId($id);


        return true;
    }

    public function __set(string $name, $value): void
    {

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
        $this->content = self::contentByFile() ?? $q->content ?? '';
        $this->img = $q->img ?? '';
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


        self::initPwImg();
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

    private function initPwImg() : void
    {
        if(self::isThumbnailCached())
            return;

        if(!$this->pwImg->exist){
            $this->pwImg = new Img('img/news/default_news_img.svg');
        }

        if($this->pwImg->extension != 'svg'){
            self::resizePw($this->pwImg->file, 260, $this->pwImg->extension);
        }
    }

    function PrintItem()
    {

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

    public function getContent() : string
    {
        return self::contentByFile() ?? $this->content ?? '';
    }

    private function contentByFile(): string|null
    {
        $file = dirname($_SERVER['DOCUMENT_ROOT']).'/includs/news/new_'.$this->id.'.php';

        if(!file_exists($file))
            return null;
        ob_start();
        include_once $file;
        return ob_get_clean();
    }

    public function PajeItem() : string
    {
        $content = $this->content ?? $this->descr;
        $ref = self::getReferer();
        return
            <<<HTML
                <div class="newsarea">
                    <div class="ntitle">$this->title</div>
                    <hr>
                    <div class="narea">
                        <div class="text">{$content}</div>
                        {$ref}
                    </div>
                </div>
            HTML;
    }

    public function getReferer() : string
    {
        if(empty($this->refLink) || empty($this->refName))
            return '';

        return
        <<<HTML
            <br><br>
            <div>
                <b>Источник: </b>
                <a href="$this->refLink">$this->refName</a>
            </div>
        HTML;
    }

    public static function apiValidation(): array|bool
    {
        if(empty($_POST)) {
            return false;
        }
        $id = $_POST['id'] ?? 0;
        $id = intval($id);
        if(!$id)
            return false;

        return ['id' => $id];
    }

    public static function getJson(int $id): bool|string
    {
        $Item = new NewsItem($id);
        return json_encode(['data' => $Item]);
    }

    public static function getCollection(int $year, array $filter = [1,2,3]) : string|bool
    {
        $qwe = qwe("
            SELECT * FROM news 
            WHERE `show` 
            AND year(date) = :year
            ORDER BY DATE DESC",
            ['year'=>$year]
        );

        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        $news = $qwe->fetchAll(PDO::FETCH_CLASS,"NewsItem") ?? [];
        if(!count($news))
            return false;

        $arr = [];
        foreach ($news as $n){
            if(!in_array($n->show,$filter))
                continue;
            if(!empty($n->cache)){
                $arr[] = json_decode($n->cache);
                continue;
            }

            $New = new NewsItem($n->id);
            $New->cache= null;
            $New->date = $New->dateFormated();

            self::writeCache($n->id,json_encode($New));
            $New->content = null;
            $arr[] = $New;
        }
        if(!count($arr)){
            return false;
        }
        return json_encode(['data' => $arr]);
    }

    private static function writeCache(int $id, $data)
    {
        qwe("UPDATE news SET cache = :data WHERE id = :id",['data' => $data, 'id'=>$id]);
    }

}