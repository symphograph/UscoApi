<?php

class Img
{
    private string $md5 = '';
    public bool $exist;
    public int $width;
    public int $height;
    public string $verLink = '';
    public string $fileName = '';
    public string $extension = '';

    /**
     * @throws ImagickException
     */
    public function __construct(
        public string $file = ''
    )

    {
        $this->file = str_replace('%20', ' ', $this->file);
        $file = $_SERVER['DOCUMENT_ROOT'].'/' . $this->file;
        $this->fileName = basename($file);
        $this->extension = pathinfo($file,PATHINFO_EXTENSION);
        $this->exist = file_exists($file) && !is_dir($file);
        if(!$this->exist){
            //printr($file);
            return;
        }

        $image = new Imagick($file);

        $this->width = $image->getImageWidth();
        $this->height = $image->getImageHeight();
        $this->verLink = self::versioner();
    }

    private function versioner(): string
    {

        if(!$this->exist){
            return '';
        }

        $file = $_SERVER['DOCUMENT_ROOT'].'/' . $this->file;
        $this->md5 = md5_file($file);

        return $this->file . '?ver=' . $this->md5;
    }

    public function tagArticle() : string
    {
        return "<img src='$this->verLink' class='newsImg'>";
    }

    public static function isImage(string $filename)
    {

        $img_types = [
            0=>'UNKNOWN',
            1=>'GIF',
            2=>'JPEG',
            3=>'PNG',
            4=>'SWF',
            5=>'PSD',
            6=>'BMP',
            7=>'TIFF_II',
            8=>'TIFF_MM',
            9=>'JPC',
            10=>'JP2',
            11=>'JPX',
            12=>'JB2',
            13=>'SWC',
            14=>'IFF',
            15=>'WBMP',
            16=>'XBM',
            17=>'ICO',
            18=>'webp'
        ];
        $is        = @getimagesize($filename);

        if(!$is)
            return false;

        if(!key_exists($is[2],$img_types))
            return false;

        return strtolower($img_types[$is[2]]);

    }

}