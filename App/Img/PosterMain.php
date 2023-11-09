<?php

namespace App\Img;

class PosterMain extends Poster
{

    protected string $folder  = '/img/posters';

    public static function getIMG(int $announceId): Img
    {
        $Poster = new self($announceId);
        return $Poster->getSizedImg();
    }



    public function upload(FileImg $file): void
    {
        $originFullPath = $this->getOriginFullPath($file->getExtension());
        $file->saveAs($originFullPath);

        $this->makeSizes($originFullPath);
    }

}