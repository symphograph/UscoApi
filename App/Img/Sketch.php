<?php

namespace App\Img;

use App\Files\UploadedImg;
use Symphograph\Bicycle\FileHelper;

abstract class Sketch extends AbstractIMG
{
    public function upload(UploadedImg $file): void
    {
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
            $fullPath = FileHelper::fullPath($relPath, true);
            FileHelper::delAllExtensions($fullPath);
        }
    }
}