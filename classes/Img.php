<?php

class Img
{
    private string $md5 = '';
    public bool $exist;
    public int $width;
    public int $height;
    public string $verLink = '';
    public string $fileName = '';

    public function __construct(
        public string $file = ''
    )

    {
        $this->file = str_replace('%20', ' ', $this->file);
        $file = $_SERVER['DOCUMENT_ROOT'].'/' . $this->file;
        $this->fileName = basename($file);
        $this->exist = file_exists($file);
        if(!$this->exist){
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

}