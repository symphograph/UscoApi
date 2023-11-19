<?php

namespace App\Img\Announce;


use App\Img\AbstractIMG;
use Symphograph\Bicycle\FileHelper;

abstract class AnnounceIMG extends AbstractIMG
{
    protected array $sizes = [480, 1080];

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







}