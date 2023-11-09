<?php

namespace App\Img;


use App\Upload\File;
use Symphograph\Bicycle\Errors\UploadErr;

class FileImg extends File
{
    public int $width  = 0;
    public int $height = 0;
    public string $ext = '';
    public int $bits = 0;

    protected function validate(): void
    {
        parent::validate();

        if(!$this->isImage()) {
            throw new UploadErr('invalid format', 'Недопустимый формат изображения.');
        }
    }

    private function isImage(): false|string
    {
        $imgTypes = [
            0  => 'SVG',
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

        try {
            $is = getimagesize($this->tmpFullPath);
        } catch (\Throwable) {
            return false;
        }

        if (!$is){
            return false;
        }

        if (!key_exists($is[2], $imgTypes)){
            return false;
        }

        $this->width = $is[0];
        $this->height = $is[1];
        $this->ext = strtolower($imgTypes[$is[2]]);
        $this->bits = $is['bits'];

        return true;
    }
}