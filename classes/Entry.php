<?php

use JetBrains\PhpStorm\Pure;

class Entry
{
    public int         $id       = 0;
    public string|null $title    = '';
    public string|null $descr    = '';
    public string|null $content  = '';
    public string|null $markdown = '';
    public array|null  $parsedMD = [];
    public string|null $img      = '';
    public string|null $date     = '';
    public int|null    $show     = 0;
    public int|null    $evid     = 0;
    public string|null $link     = '';
    public string|null $refName  = '';
    public string|null $refLink  = '';
    public Img|bool    $pwImg    = false;
    public string|null $cache    = '{}';
    public array       $Images   = [];
    public string|null $html     = '';
    public array $usedImages = [];
    public array $unusedImages = [];

    public function __set($name, $value) {}

    public static function byID(int $id) : Entry|bool
    {
        $qwe = qwe("SELECT * FROM news WHERE id = :id",compact('id'));
        if(!$qwe || !$qwe->rowCount())
            return false;

        $q = $qwe->fetchAll(PDO::FETCH_CLASS,'Entry')[0] ?? false;
        return self::byQ($q);
    }

    public static function byQ(Entry $Entry) : Entry|bool
    {
        $images = self::getImages($Entry->id);
        $Entry->Images = self::getAllImages($Entry->id, $images);
        $Entry->parsedMD = self::explodeHTMLByTags($Entry->markdown);
        $Entry->usedImages = self::getUsedImages($Entry->parsedMD);
        $Entry->unusedImages = $Entry->getUnusedImages();
        return $Entry;
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

    /**
     * Возвращает массив имен
     */
    public static function getImages(int $id) : array
    {
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/img/entry/1080/' . $id;
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
            $arr[] = new Img('/img/entry/1080/' . $id . '/' . $img);
        }
        return $arr;
    }

    public static function parseMarkdown($markdown) : array
    {
        $markdown = explode(PHP_EOL,$markdown);
        $arr = [];
        foreach ($markdown as $k => $md) {
            preg_match_all('#!\[]\((.*?)\)#', $md, $res);

            if (!empty($res[1][0])) {
                foreach ($res[1] as $r) {
                    $arr[] = [
                        'type'    => 'img',
                        'content' => $r
                    ];
                }
                continue;
            }
            if(empty($md)){
                $md = PHP_EOL;
            }
            $arr[] = [
                'type'    => 'text',
                'content' => $md
            ];
        }
        return $arr;
        //return json_encode($arr,JSON_UNESCAPED_UNICODE);
    }

    public static function explodeHTMLByTags(string $text): array
    {
        $parts = self::explodeHTMLByImg($text);
        $partsByVideo = [];
        foreach ($parts as $part){
            if($part['type'] != 'text'){
                $partsByVideo[] = $part;
                continue;
            }
            $partsV = self::explodeHTMLByVideo($part['content']);
            foreach ($partsV as $p){
                $partsByVideo[] = $p;
            }
        }
        return $partsByVideo;
    }

    public static function explodeHTMLByImg(string $text): array
    {
        preg_match_all('#!\[]\((.*?)\)#', $text, $res);
        if(empty($res[0])){
            return [['type' => 'text', 'content' => $text]];
        }
        $newText = $text;
        $parts = [];
        foreach ($res[0] as $k => $img){
            $exoloded = explode($img,$newText);
            $src = $res[1][$k];
            $parts[] = ['type' => 'text', 'content' => $exoloded[0]];
            $parts[] = ['type' => 'img', 'content' => $src];
            if(empty($exoloded[1])) continue;
            $newText = $exoloded[1];
        }
        $parts[] = ['type' => 'text', 'content' => $newText];
        return $parts;
    }

    public static function explodeHTMLByVideo(string $text): array
    {
        preg_match_all('#!\[video]\((.*?)\)#', $text, $res);
        if(empty($res[0])){
            return [['type' => 'text', 'content' => $text]];
        }
        $newText = $text;
        $parts = [];
        foreach ($res[0] as $k => $img){
            $exoloded = explode($img,$newText);
            $src = $res[1][$k];
            $parts[] = ['type' => 'text', 'content' => $exoloded[0]];
            $parts[] = ['type' => 'video', 'content' => $src];
            if(empty($exoloded[1])) continue;
            $newText = $exoloded[1];
        }
        $parts[] = ['type' => 'text', 'content' => $newText];
        return $parts;
    }

    public function putToDB(): bool|PDOStatement
    {
        return qwe("
                REPLACE INTO news 
                (id, title, descr, content, markdown, img, date, `show`, evid, refName, refLink, cache, html) 
                        VALUES 
               (:id, :title, :descr, :content, :markdown, :img, :date, :show, :evid, :refName, :refLink, null, :html)",
            [
                'id'       => $this->id,
                'title'    => $this->title,
                'descr'    => $this->descr,
                'content'  => $this->content,
                'markdown' => $this->markdown,
                'img'      => $this->img,
                'date'     => $this->date,
                'show'     => $this->show,
                'evid'     => $this->evid,
                'refName'  => $this->refName,
                'refLink'  => $this->refLink,
                'html'    => $this->html
        ]);
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

    private function getUnusedImages(): array
    {
        return array_diff(self::getImages($this->id),$this->usedImages);
    }


}