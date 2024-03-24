<?php

namespace App\Img\Announce;

use Symphograph\Bicycle\Files\UploadedImg;
use App\Img\AbstractIMG;
use Symphograph\Bicycle\FileHelper;

class AnnouncePoster extends AbstractIMG
{
    protected array $sizes = [480, 1080];
    protected string $folder  = '/img/posters/poster';
    protected string $filePrefix = 'poster_';


    public function upload(UploadedImg $file): void
    {
        $originFullPath = $this->getOriginFullPath($file->getExtension());
        $file->saveAs($originFullPath);

        $this->makeSizes($originFullPath);
    }

    protected function getOriginBaseName(string $ext): string
    {
        return $this->filePrefix . $this->id . '.' . $ext;
    }

    protected function getSizedBaseName(): string
    {
        return $this->filePrefix . $this->id . '.jpg';
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