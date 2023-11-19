<?php

namespace App\Img;

use Symphograph\Bicycle\FileHelper;

abstract class Photo extends AbstractIMG
{
    protected string $baseName;

    public function __construct($id, string $baseName = '') {
        parent::__construct($id);
        $this->baseName = $baseName;
        $this->initFolder();
    }
    public function initFolder(): void
    {
        $this->folder = $this->parentFolder . '/' . $this->id;
    }

    protected function getSizedBaseName(): string
    {
        return pathinfo($this->baseName,  PATHINFO_FILENAME) . '.jpg';
    }

    protected function getOriginBaseName(string $ext = ''): string
    {
        return $this->baseName;
    }

    /*
    protected function makeSize(int $with, string $from, string $to): void
    {
        $image = new Imagick($from);
        $image->setImageFormat("jpeg") or throw new ImgErr();

        $image->stripimage();
        $image->setImageResolution(72, 72);
        $image->resampleImage(72, 72, \Imagick::FILTER_LANCZOS, 1);
        $image->resizeImage($with, 0, 0, 1);

        FileHelper::fileForceContents($to, $image->getImageBlob());
    }
*/
    public function delFiles(): void
    {
        $sizeFolders = $this->sizes;
        $sizeFolders[] = 'origins';
        $fileName = pathinfo($this->baseName, PATHINFO_FILENAME);

        foreach ($sizeFolders as $sizeFolder) {

            $relPath = $this->folder . '/' . $sizeFolder . '/' . $fileName;
            $fullPath = FileHelper::fullPath($relPath);
            FileHelper::delAllExtensions($fullPath);
        }
    }

    public function upload(FileImg $file): void
    {
        $this->baseName = md5_file($file->tmpFullPath) . '.' . $file->getExtension();
        $originFullPath = $this->getOriginFullPath($file->getExtension());
        $file->saveAs($originFullPath);

        $this->makeSizes($originFullPath);
    }
}