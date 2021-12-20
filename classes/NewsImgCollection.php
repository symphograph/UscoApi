<?php

class NewsImgCollection
{
    public string $imgFolder = '';
    public array $files = [];

    public function __construct(
        public int $newId,
        public int $start = 0,
        public array $alradyUsed = []
    )
    {
        require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/filefuncts.php';
        $this->imgFolder = 'img/news/' . $newId . '/';
        $this->files = FileList($this->imgFolder);
        if(!count($this->files)){
            return;
        }
        self::filterUsed();
        self::clipStart();
    }

    private function clipStart() : void
    {
        $files = [];
        foreach ($this->files as $file){
            $fileName = pathinfo($file,PATHINFO_FILENAME);
            if(!intval($fileName)){
                continue;
            }

            if($this->start > intval($fileName)){
                continue;
            }
            $files[] = $file;
        }
        $this->files = $files;
    }

    private function filterUsed() : void
    {
        if(!count($this->alradyUsed)){
            return;
        }
        $files = [];
        foreach ($this->files as $file){
            if(in_array($file,$this->alradyUsed)){
                continue;
            }
            $files[] = $file;
        }
        $this->files = $files;
    }

    public static function getLinks(
        int   $newId,
        int   $start = 0,
        array $alradyUsed = []
    ): array
    {
        $Images = new NewsImgCollection($newId,$start,$alradyUsed);
        $links = [];
        foreach ($Images->files as $file){
            $link = (new Img($Images->imgFolder . $file))->verLink;
            if(empty($link)){
                continue;
            }
            $links[] = $link;
        }
        return $links;
    }

    public static function printImages(array $images) : string
    {
        if(!count($images)){
            return '<br>Изображения не найдены<br>';
        }
        $arr = [];
        foreach ($images as $img){
            $arr[] = Img::printInNews($img);
        }
        return implode('<br><br>',$arr);
    }
}