<?php

namespace App\Img\Announce;

use App\Img\Sketch;

class AnnounceSketch extends Sketch
{
    protected array $sizes = [480, 1080];
    protected string $folder  = '/img/posters/sketch';
    protected string $filePrefix = 'poster_';

}