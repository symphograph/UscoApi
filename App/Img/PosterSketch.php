<?php

namespace App\Img;



use Symphograph\Bicycle\Errors\ImgErr;

class PosterSketch extends Poster
{
    const folder = '/img/posters/topp';


    protected string $folder  = '/img/posters/topp';


    public static function getIMG(int $announceId): Img
    {
        $Sketch = new self($announceId);
        return $Sketch->getSizedImg();
    }


    public function upload(FileImg $file): void
    {

        if($file->height > $file->width){
            throw new ImgErr('Invalid ratio', 'Недопустимое соотношение сторон');
        }
        $originFullPath = $this->getOriginFullPath($file->getExtension());
        $file->saveAs($originFullPath);

        $this->makeSizes($originFullPath);
    }
}