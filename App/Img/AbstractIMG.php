<?php

namespace App\Img;

use Imagick;
use ImagickPixel;
use Symphograph\Bicycle\Errors\ImgErr;
use Symphograph\Bicycle\FileHelper;

abstract class AbstractIMG
{
    protected int    $id;
    protected array  $sizes;
    protected string $folder;
    protected string $filePrefix;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    private static function processResize(Imagick $image, int $width): Imagick
    {
        $image = self::removePNGBackground($image);
        $image->setImageFormat("jpeg") or throw new ImgErr();
        $image->stripimage();

        $resolution = self::getResolution($width);
        $image->setImageResolution($resolution, $resolution);
        $image->resampleImage($resolution, $resolution, Imagick::FILTER_LANCZOS, 1);
        $image->resizeImage($width, 0, 0, 1);
        return $image;
    }

    public function makeSizes(string $originFullPath): void
    {
        foreach ($this->sizes as $with) {
            $newFullPath = $this->getSizedFullPath($with);
            $this->makeSize($with, $originFullPath, $newFullPath);
        }
    }

    public function getSizedFullPath(int $width): string
    {
        $relPath = $this->getSizedRelPath($width);
        return FileHelper::fullPath($relPath, true);
    }

    public function getSizedRelPath(int $with): string
    {
        return $this->folder . '/' . $with . '/' . $this->getSizedBaseName();
    }

    protected function makeSize(int $width, string $from, string $to): void
    {
        $image = new Imagick($from);
        $image = self::processResize($image, $width);

        FileHelper::fileForceContents($to, $image->getImageBlob());
    }

    private static function getResolution(int $width): int
    {
        return $width < 1080 ? 72 : 96;
    }

    private static function removePNGBackground(Imagick $image): Imagick
    {
        $data = $image->identifyImage();
        if ($data['mimetype'] !== 'image/png') return $image;

        $image->setBackgroundColor(new ImagickPixel('transparent'));
        return $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
    }

    public function getOriginFullPath(string $ext): string
    {
        $relPath = $this->folder . '/origins/' . $this->getOriginBaseName($ext);
        return FileHelper::fullPath($relPath, true);
    }

}