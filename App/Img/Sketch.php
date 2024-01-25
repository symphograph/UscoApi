<?php

namespace App\Img;


use Symphograph\Bicycle\Errors\ImgErr;
use Symphograph\Bicycle\FileHelper;

abstract class Sketch extends AbstractIMG
{
    public function upload(FileImg $file): void
    {
        if(!$file->isAspectRatio16x9()){
            throw new ImgErr('Invalid ratio', 'Недопустимое соотношение сторон');
        }
        $originFullPath = $this->getOriginFullPath($file->getExtension());
        $file->saveAs($originFullPath);

        $this->makeSizes($originFullPath);
    }

    protected function getSizedBaseName(): string
    {
        return $this->filePrefix . $this->id . '.jpg';
    }

    protected function getOriginBaseName(string $ext): string
    {
        return $this->filePrefix . $this->id . '.' . $ext;
    }

    public function delFiles(): void
    {
        $sizeFolders = $this->sizes;
        $sizeFolders[] = 'origins';
        foreach ($sizeFolders as $sizeFolder) {
            $relPath = $this->folder . '/' . $sizeFolder . '/' . $this->filePrefix . $this->id;
            $fullPath = FileHelper::fullPath($relPath);
            FileHelper::delAllExtensions($fullPath);
        }
    }
}