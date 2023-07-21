<?php
namespace App;
use Symphograph\Bicycle\FileHelper;

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
        //require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/filefuncts.php';
        $this->imgFolder = 'img/news/' . $newId . '/';
        $this->files = FileHelper::fileList($this->imgFolder);
        if(!count($this->files)){
            return;
        }
        self::filterUsed();
        //printr($this->files);
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
            if(in_array(self::fileAsNum($file),$this->alradyUsed)){
                continue;
            }
            $files[] = $file;
        }
        $this->files = $files;
    }

    private function fileAsNum(string|int $file) : int
    {
        $file = pathinfo($file, PATHINFO_FILENAME);
        return intval($file);
    }

    public static function getLinks(
        int   $newId,
        int   $start = 0,
        array $alradyUsed = []
    ): array
    {
        $Images = new NewsImgCollection($newId,$start,$alradyUsed);
        $files = [];
        foreach ($Images->files as $file){
            $file = $Images->imgFolder . $file;
            $files[] = $file;
        }
        return $files;
    }

    public static function printImages(
        int   $newId,
        int   $start = 0,
        array $alradyUsed = []
    ): string
    {
        $images = self::getLinks($newId, $start,$alradyUsed);
        if(!count($images)){
            return '<br>Изображения не найдены<br>';
        }
        $arr = [];
        foreach ($images as $img){
            $arr[] = Img::printInNews($newId,$img);
        }
        return implode( PHP_EOL.PHP_EOL ,$arr);
    }
}