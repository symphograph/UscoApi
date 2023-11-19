<?php

namespace App\Img\Entry;

use App\Img\FileImg;
use App\Img\Photo;
use Imagick;
use Symphograph\Bicycle\Errors\ImgErr;
use Symphograph\Bicycle\FileHelper;

class EntryPhoto extends Photo
{
    protected array $sizes = [260, 1080];
    protected string $parentFolder = '/img/entry/photo';
    protected string $baseName;





}