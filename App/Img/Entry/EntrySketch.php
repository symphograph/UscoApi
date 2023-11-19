<?php

namespace App\Img\Entry;

use App\Img\Sketch;

class EntrySketch extends Sketch
{
    protected array $sizes = [260, 1080];
    protected string $folder  = '/img/entry/sketch';
    protected string $filePrefix = 'entry_';

}