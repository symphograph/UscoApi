<?php

use JetBrains\PhpStorm\Pure;

class Entry
{
    const imgFolder = '/img/entry/1080/';
    const defaultCategs = [
        0 => false,
        1 => false,
        2 => false,
        3 => false
    ];
    public int         $id           = 0;
    public string|null $title        = '';
    public string|null $descr        = '';
    public string|null $content      = '';
    public string|null $markdown     = '';
    public array|null  $parsedMD     = [];
    public string|null $img          = '';
    public string|null $date         = '';
    public int|null    $show         = 0;
    public int|null    $evid         = 0;
    public string|null $link         = '';
    public string|null $refName      = '';
    public string|null $refLink      = '';
    public Img|bool    $pwImg        = false;
    public string|null $cache        = '{}';
    public array       $Images       = [];
    public string|null $html         = '';
    public array       $usedImages   = [];
    public array       $unusedImages = [];
    public string|null $catindex     = '0|0|0|0';
    public string|null $concategs    = '';
    public array       $categs       = [];

    public function __set($name, $value) {}

    public static function byID(int $id) : Entry|bool
    {
        $qwe = qwe("
            SELECT news.*, 
            GROUP_CONCAT(nn.categ_id) as concategs 
            FROM news 
            INNER JOIN nn_EntryCategs as nn ON news.id = nn.entry_id
            AND id = :id",
            compact('id')
        );
        if(!$qwe || !$qwe->rowCount())
            return false;

        $q = $qwe->fetchAll(PDO::FETCH_CLASS,'Entry')[0] ?? false;
        return self::byQ($q);
    }

    public static function byQ(Entry $Entry) : Entry|bool
    {
        $images = self::getImages($Entry->id);
        $Entry->img = self::getPw($Entry->id);
        $Entry->Images = self::getAllImages($Entry->id, $images);
        $Entry->parsedMD = self::explodeHTMLByTags($Entry->markdown);
        $Entry->usedImages = self::getUsedImages($Entry->parsedMD);
        $Entry->unusedImages = $Entry->getUnusedImages();
        $Entry->categs = self::categsByConcategs($Entry->concategs ?? '');
        if(empty($Entry->date)){
            $Entry->date = date('Y-m-d');
        }
        return $Entry;
    }

    public static function categsByConcategs(string $concategs): array
    {
        $arr = self::defaultCategs;
        $categs = explode(',',$concategs);
        foreach (self::defaultCategs as $k => $v){
            $arr[$k] = in_array($k,$categs);
        }
        return $arr;
    }

    #[Pure] public static function clone(Entry $q) : Entry
    {
        $Entry = new Entry();
        foreach ($q as $k => $v){
            if(empty($v)) continue;
            $Entry->$k = $v;
        }
        return $Entry;
    }

    public static function getAlldbRows() : array
    {
        $qwe = qwe("SELECT * FROM news");
        if(!$qwe || !$qwe->rowCount())
            return [];

        return $qwe->fetchAll(PDO::FETCH_CLASS,'Entry') ?? [];
    }

    public static function getCollection(int $year = 0, array $categs = self::defaultCategs,int $limit = 1000) : array
    {
        $categs = array_filter($categs);
        $categs = array_keys($categs);
        $pHoldels = DB::pHolders($categs);

        $params = DB::pHoldsArr($categs);
        //printr($params);
        $params['year'] = $year ?? date('Y');
        $limit = ' LIMIT ' . $limit;


        $qwe = qwe("
            SELECT news.*, 
            GROUP_CONCAT(nn.categ_id) as concategs FROM news
            INNER JOIN nn_EntryCategs as nn ON news.id = nn.entry_id
            AND nn.categ_id in ($pHoldels)
            AND YEAR(news.date) = :year
            GROUP BY news.id
            ORDER BY news.date DESC" . $limit,
            $params
        );
        if(!$qwe || !$qwe->rowCount())
            return [];

        $rows = $qwe->fetchAll(PDO::FETCH_CLASS,'Entry');

        $Entryes = [];
        foreach ($rows as $Entry){
            $Entryes[] = self::byQ($Entry);
        }
        return $Entryes;
    }

    /**
     * Возвращает массив имен
     */
    public static function getImages(int $id) : array
    {
        $dir = $_SERVER['DOCUMENT_ROOT'] . Entry::imgFolder . $id;
        return FileHelper::FileList($dir);
    }

    /**
     * Возвращает массив имен
     */
    public static function getOldImages(int $id) : array
    {
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/img/news/' . $id;
        return FileHelper::FileList($dir);
    }

    /**
     * Возвращает массив объектов Img
     */
    public static function getAllImages(int $id, array $images): array
    {
        $arr = [];
        foreach ($images as $img){
            $arr[] = new Img(Entry::imgFolder . $id . '/' . $img);
        }
        return $arr;
    }

    public static function explodeHTMLByTags(string $text): array
    {
        $parts = self::explodeHTMLByType($text,'img');
        $partsByVideo = [];
        foreach ($parts as $part){
            if($part['type'] != 'text'){
                $partsByVideo[] = $part;
                continue;
            }
            $partsV = self::explodeHTMLByType($part['content'],'video');
            foreach ($partsV as $p){
                $partsByVideo[] = $p;
            }
        }
        return $partsByVideo;
    }

    public static function explodeHTMLByType(string $text, string $tag = 'img'): array
    {
        if(empty($text))
            return [];

        $pregs = [
            'img'=>'#!\[]\((.*?)\)#',
            'video' => '#!\[video]\((.*?)\)#'
            ];

        $preg = $pregs[$tag] ?? false;
        if(!$preg) {
            return [['type' => 'text', 'content' => $text]];
        }


        preg_match_all($preg, $text, $res);

        if (empty($res[0]))
            return [['type' => 'text', 'content' => $text]];

        $newText = $text . '---end';
        $parts = [];
        foreach ($res[0] as $k => $val) {
            $exoloded = explode($val, $newText);

            $part = str_replace('---end', '', $exoloded[0]);
            $part = trim($part);
            if (!empty($part))
                $parts[] = ['type' => 'text', 'content' => $part];

            $src = $res[1][$k];
            $parts[] = ['type' => $tag, 'content' => $src];

            if (empty($exoloded[1])) continue;
            $newText = $exoloded[1];
        }
        $newText = str_replace('---end', '', $newText);
        $newText = trim($newText);
        if (!empty($newText))
            $parts[] = ['type' => 'text', 'content' => $newText];

        return $parts;
    }

    public function putToDB(): bool|PDOStatement
    {
        $qwe = qwe("
                REPLACE INTO news 
                (id, title, descr, content, markdown, date, `show`, evid, refName, refLink) 
                        VALUES 
               (:id, :title, :descr, :content, :markdown, :date, :show, :evid, :refName, :refLink)",
            [
                'id'       => $this->id,
                'title'    => $this->title,
                'descr'    => $this->descr,
                'content'  => $this->content,
                'markdown' => $this->markdown,
                'date'     => $this->date,
                'show'     => $this->show,
                'evid'     => $this->evid,
                'refName'  => $this->refName,
                'refLink'  => $this->refLink
        ]);
        if(!$qwe)
            return false;
        qwe("DELETE FROM nn_EntryCategs WHERE entry_id = :id",['id'=>$this->id]);
        $categs = array_filter($this->categs);
        foreach ($categs as $k => $v){
            qwe("REPLACE INTO nn_EntryCategs (entry_id, categ_id) VALUES (:id,:cid)",
            ['id'=>$this->id,'cid'=>$k]
            );
        }
        self::reCache($this->id);
        return true;
    }

    public static function reCache(int $id): bool|PDOStatement
    {
        $Entry = Entry::byID($id);
        return qwe("UPDATE news SET cache = :cache WHERE id = :id",
        ['id' => $id, 'cache' => json_encode($Entry)]
        );
    }

    public static function getUsedImages(array $parsedMD): array
    {
        $arr = [];
        foreach ($parsedMD as $section){
            if($section['type'] == 'img'){
                $arr[] = $section['content'] ?? null;
            }
        }
        return $arr;
    }

    private function getUnusedImages() : array
    {
        return array_diff(self::getImages($this->id),$this->usedImages);
    }

    #[Pure] public function htmlByMD() : string
    {
        if(empty($this->parsedMD))
            return '';

        $content = '';
        foreach ($this->parsedMD as $section) {

            if (empty($section))
                continue;

            if ($section['type'] == 'text')
                $content .= '<section>' . $section['content'] . '</section>';

            if ($section['type'] == 'img') {
                $src = Entry::imgFolder . $this->id . '/' . $section['content'];
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $src))
                    $content .= "<section class='section-img'><img src='$src' class='newsImg' alt='Изображение'></section>";
            }

            if ($section['type'] == 'video') {
                $content .= '<section class="section-video">' . Video::getIFrame($section['content']) . '</section>';
            }
        }

        return $content;
    }

    #[Pure] public function htmlPaje() : string
    {
        $content = $this->htmlByMD();

        $ref = self::getReferer();
        $result =
            <<<HTML
                <div class="newsarea">
                    <div class="ntitle">$this->title</div>
                    <hr>
                    <div class="narea">
                        <div class="text">$content</div>
                        $ref
                    </div>
                </div>
            HTML;
        return $result;
    }

    public function getReferer() : string
    {
        if (empty($this->refLink) || empty($this->refName))
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

    public static function categsByShow(int $show): array
    {
        return match ($show)
        {
            1 => [
                0 => false,
                1 => true,
                2 => false,
                3 => false
            ],
            2 => [
                0 => false,
                1 => false,
                2 => false,
                3 => true
            ],
            3 => [
                0 => false,
                1 => true,
                2 => true,
                3 => false
            ],
            4 => [
                0 => true,
                1 => true,
                2 => true,
                3 => true
            ],
            default => self::defaultCategs
        };
    }

    private static function getPw(int $id): string
    {
        $size = 260;
        $agent = get_browser();
        if($agent->ismobiledevice) {
            $size = 1080;
        }
        $path = '/img/entry/' . $size . '/' . $id . '/pw/';
        $fileName = FileHelper::FileList($path)[0] ?? false;
        if(!$fileName){
            return '/img/entry/default.svg';
        }
       return $path . $fileName;
    }

    public static function delPw(int $id)
    {
        $sizes = [260,1080];
        foreach ($sizes as $size){
            $dir = '/img/entry/' . $size . '/' . $id . '/pw/';
            FileHelper::delDir($dir);
        }
    }

    public function getLink(): string
    {
        if($this->evid)
            return 'event.php?evid=' . $this->evid;

        return 'new.php?new_id=' . $this->id;
    }

    public function PrintItem(): string
    {
        $fDate = ru_date('',strtotime($this->date));
        $img = $this->img;
        $link = self::getLink();
        $title = $this->title;
        $descr = $this->descr;

        return <<<HTML
        <div class="narea">
            <div class="nimg_block">
                <div>
                    <a href="$link">
                        <img src="$img" width="260px" alt="Изображение"/>
                    </a>
                </div>
            </div>
            <div class="tcol">
                <div class="ntitle">
                    <a href="$link">
                        <b>$title</b>
                    </a>
                </div>
                <br>
                <a href="$link">
                    $descr
                </a>
                <br><br>
                <span class="ndate">$fDate</span>
                <br>
            </div>
        </div><br><hr><br>
        HTML;
    }

    private static function createNewID() : int
    {
        $qwe = qwe("SELECT max(id)+1 as id FROM news");
        if(!$qwe or !$qwe->rowCount()){
            return 0;
        }
        return $qwe->fetchObject()->id ?? 0;
    }

    public static function addNewEntry(): bool|Entry
    {
        $id = self::createNewID();
        if(!$id)
            return false;

        $Entry = Entry::byId(1);
        if(!$Entry)
            return false;
        $Entry->id = $id;
        if(!$Entry->putToDB())
            return false;

        return $Entry;
    }

}