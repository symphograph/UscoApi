<?php

namespace App\Img;

use Symphograph\Bicycle\FileHelper;

class PhotoList
{
    protected string $folder;
    protected array $list;

    /**
     * @return string[]
     */
    public function getOriginBaseNames(): array
    {
        return FileHelper::FileList($this->folder . '/origins');
    }


    /**
     * @param int $size
     * @return string[]
     */
    public function getSizedRelPathList(int $size): array
    {
        $arr = [];
        foreach ($this->list as $photo){
            $arr[] = $photo->getSizedRelPath($size);
        }
        return $arr;
    }


}