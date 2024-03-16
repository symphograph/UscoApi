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

    public function delFiles(): void
    {
        $sizeFolders = $this->sizes;
        $sizeFolders[] = 'origins';
        $fileName = pathinfo($this->baseName, PATHINFO_FILENAME);

        foreach ($sizeFolders as $sizeFolder) {

            $relPath = $this->folder . '/' . $sizeFolder . '/' . $fileName;
            $fullPath = FileHelper::fullPath($relPath, true);
            FileHelper::delAllExtensions($fullPath);
        }
    }

}