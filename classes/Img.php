<?php

class Img
{
    public string $md5 = '';
    public bool $exist = false;
    public int $width = 0;
    public int $height = 0;
    public string $verLink = '';
    public string $fileName = '';
    public string $ext      = '';


    public function __construct(
        public string $file = ''
    )
    {
        return self::initFileInfo($file);
    }

    protected function initFileInfo(string $file) : bool
    {
        if(empty($file)){
            return false;
        }
        $this->file = str_replace('%20', ' ', $this->file);
        $file = $_SERVER['DOCUMENT_ROOT'].'/' . $this->file;
        $this->fileName = basename($file);
        $this->ext = pathinfo($file,PATHINFO_EXTENSION);
        $this->exist = file_exists($file) && !is_dir($file);
        if(!$this->exist){
            //printr($file);
            return false;
        }

        try {
            $image = new Imagick($file);

            $this->width = $image->getImageWidth();
            $this->height = $image->getImageHeight();
            $this->verLink = self::versioner();

            return true;
        } catch (ImagickException $e){
            return false;
        }


    }

    private function versioner(): string
    {

        if(!$this->exist){
            return '';
        }

        $file = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->file;
        $this->md5 = md5_file($file);

        return $this->file . '?ver=' . $this->md5;
    }

    public static function getVerLink(string $file) : string
    {
        return (new Img($file))->verLink;
    }

    public static function isImage(string $file): bool|string
    {
        $img_types = [
            0 => 'SVG',
            1  => 'GIF',
            2  => 'JPEG',
            3  => 'PNG',
            4  => 'SWF',
            5  => 'PSD',
            6  => 'BMP',
            7  => 'TIFF_II',
            8  => 'TIFF_MM',
            9  => 'JPC',
            10 => 'JP2',
            11 => 'JPX',
            12 => 'JB2',
            13 => 'SWC',
            14 => 'IFF',
            15 => 'WBMP',
            16 => 'XBM',
            17 => 'ICO',
            18 => 'webp'
        ];
        $is        = @getimagesize($file);

        if(!$is)
            return false;

        if(!key_exists($is[2],$img_types))
            return false;

        return strtolower($img_types[$is[2]]);

    }

    public static function printInNews(int $id, string $file) : string
    {
        if(empty($file)){
            return '';
        }

        //EntryImg::saveFromOld($file,$id);
        $oldName = pathinfo($file,PATHINFO_BASENAME);
        $newName = EntryImg::newName($id,$oldName);
        //printr($newName);

        if(
            str_starts_with($_SERVER['SCRIPT_NAME'],'/api/')
            ||
            str_starts_with($_SERVER['SCRIPT_NAME'],'/test')
        ){
            $img = "![]($newName)";
           // $link = 'https://' . $_SERVER['SERVER_NAME'] . '/' . $link;
        }else{
            $link = '/img/entry/1080/'. $id . '/' .$newName;
            $img = "<img src='$link' class='newsImg'>";
        }

        //return "<img src='$link' class='newsImg'>";
        return $img;
    }

    public static function uplErors(array $file) : string|bool
    {
        if (!empty($file['error']) || empty($file['tmp_name']) || $file['tmp_name'] == 'none') {
            return 'Не удалось загрузить файл.';
        }

        if (empty($file['name']) || !is_uploaded_file($file['tmp_name'])) {
            return 'Ошибка при загрузке.';
        }

        if (!self::isImage($file['tmp_name'])){
            return 'Недопустимый формат изображения.';
        }

        if($file['size'] > 50000000){
            return 'Файл слишком большой.';
        }

        return false;
    }

    public static function imgSizeOptimal(): int
    {
        $agent = get_browser();
        if($agent->ismobiledevice) {
            return 1080;
        }

        return 480;
    }

    public static function optimize(string $from, string $to, int $width = 0, int $height = 0): bool
    {
        try {
            $image = new Imagick($_SERVER['DOCUMENT_ROOT'] . '/' . $from);
            $data = $image->identifyImage();
            if ($data['mimetype'] == 'image/png')
            {
                //printr($data);
                $image->setBackgroundColor(new ImagickPixel('transparent'));
                $image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            }
            if (!$image->setImageFormat("jpeg"))
                return false;
            $image->stripimage();
            $image->setImageResolution(96, 96);
            $image->resampleImage(96, 96, \Imagick::FILTER_LANCZOS, 1);

            if($width || $height){
                $image->resizeImage($width, $height, 0, 1);
            }
            $image->setCompression(Imagick::COMPRESSION_JPEG);
            $image->setImageCompressionQuality(70);
            FileHelper::forceDir($to,1);
            $image->writeImage($_SERVER['DOCUMENT_ROOT'] . '/' . $to);


        } catch (ImagickException $e){
            return false;
        }
        return true;
    }

}