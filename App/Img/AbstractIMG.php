<?php

namespace App\Img;

use Imagick;
use ImagickPixel;
use Symphograph\Bicycle\Errors\ImgErr;
use Symphograph\Bicycle\FileHelper;

abstract class AbstractIMG
{
    protected int $id;
    protected array $sizes;
    protected string $folder;
    protected string $filePrefix;

    public function __construct(int $id) {
        $this->id = $id;
    }

    public function getSizedFullPath(int $with): string
    {
        $relPath = $this->getSizedRelPath($with);
        return FileHelper::fullPath($relPath);
    }

    public function getSizedRelPath(int $with): string
    {
        return $this->folder . '/' . $with . '/' . $this->getSizedBaseName();
    }



    public function makeSizes(string $originFullPath): void
    {
        foreach ($this->sizes as $with) {
            $newFullPath = $this->getSizedFullPath($with);
            $this->makeSize($with, $originFullPath, $newFullPath);
        }
    }

    protected function makeSize(int $with, string $from, string $to): void
    {
        $resolution = 96;
        if($with < 1080){
            $resolution = 72;
        }
        $image = new Imagick($from);
        $data = $image->identifyImage();
        if ($data['mimetype'] == 'image/png') {
            $image->setBackgroundColor(new ImagickPixel('transparent'));
            $image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        }
        $image->setImageFormat("jpeg") or throw new ImgErr();

        $image->stripimage();
        $image->setImageResolution($resolution, $resolution);
        $image->resampleImage($resolution, $resolution, Imagick::FILTER_LANCZOS, 1);
        $image->resizeImage($with, 0, 0, 1);

        FileHelper::fileForceContents($to, $image->getImageBlob());
    }

    protected function getOriginFullPath(string $ext): string
    {
        $relPath = $this->folder . '/origins/' . $this->getOriginBaseName($ext);
        return FileHelper::fullPath($relPath);
    }






}