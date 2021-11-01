<?php

class Img
{
    private string $md5 = '';
    private bool $exist;
    public int $width;
    public int $height;
    public string $verLink = '';

    public function __construct(
        public string $file = ''
    )

    {
        $file = $_SERVER['DOCUMENT_ROOT'].'/' . $this->file;
        $this->exist = file_exists($file);
        if(!$this->exist)
            return;

        $size = getimagesize($file);
        $this->width = $size[0];
        $this->height = $size[1];

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