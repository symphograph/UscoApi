<?php

class Img
{
    private string $md5 = '';

    public function __construct(
        private string $file = ''
    )
    {

    }

    private function versioner()
    {
        $file = $_SERVER['DOCUMENT_ROOT'].'/' . $this->file;
        if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/' . $this->file)){
            return '';
        }

        $this->md5 = md5_file($file);

        return $this->file . '?ver=' . $this->md5;

    }

    public function tagArticle() : string
    {
        $url = self::versioner();
        return "<img src='$url' class='newsImg'>";
    }


}