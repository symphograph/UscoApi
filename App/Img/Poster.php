<?php

namespace App\Img;


use Imagick;
use Symphograph\Bicycle\Errors\ImgErr;
use Symphograph\Bicycle\FileHelper;

abstract class Poster
{
    const sizes = [480, 1080];
    const filePrefix = 'poster_';

    public int $announceId;
    protected string $folder;


    public function __construct(int $announceId) {
        $this->announceId = $announceId;
    }

    protected function getSizedImg(): Img
    {
        $with = Img::getOptimalSize();
        $relPath = $this->getSizedRelPath($with);
        return new Img($relPath);
    }

    private function originFullPathIfExists(): string|false
    {
        $extensions = FileHelper::getExtensionsInAllCases();

        foreach ($extensions as $ext) {
            $fullPath = $this->getOriginFullPath($ext);
            if(FileHelper::fileExists($fullPath)){
                return $fullPath;
            }
        }
        return false;
    }

    protected function makeSizes(string $originFullPath): void
    {
        foreach (self::sizes as $with) {
            $newFullPath = $this->getSizedFullPath($with);
            $this->makeSize($with, $originFullPath, $newFullPath);
        }
    }

    protected function makeSize(int $with, string $from, string $to): void
    {
        $image = new Imagick($from);
        $image->setImageFormat("jpeg") or throw new ImgErr();

        $image->stripimage();
        $image->setImageResolution(72, 72);
        $image->resampleImage(72, 72, \Imagick::FILTER_LANCZOS, 1);
        $image->resizeImage($with, 0, 0, 1);

        $image->writeImage($to)
        or throw new ImgErr('writeImage error', 'Ошибка при сохранении изображения');
    }

    protected function getSizedFullPath(int $with): string
    {
        $relPath = $this->getSizedRelPath($with);
        return FileHelper::fullPath($relPath);
    }

    protected function getSizedRelPath(int $with): string
    {
        return $this->folder . '/' . $with . '/' . $this->getSizedFileName();
    }

    protected function getOriginFullPath(string $ext): string
    {
        $relPath = $this->folder . '/origins/' . $this->getOriginFileName($ext);
        return FileHelper::fullPath($relPath);
    }

    protected function getOriginFileName(string $ext): string
    {
        return self::filePrefix . $this->announceId . '.' . $ext;
    }

    protected function getSizedFileName(): string
    {
        return self::filePrefix . $this->announceId . '.jpg';
    }

    public function delFiles(): void
    {
        $sizeFolders = self::sizes;
        $sizeFolders[] = 'origins';
        foreach ($sizeFolders as $sizeFolder) {
            $relPath = $this->folder . '/' . $sizeFolder . '/' . self::filePrefix . $this->announceId;
            $fullPath = FileHelper::fullPath($relPath);
            FileHelper::delAllExtensions($fullPath);
        }
    }

}