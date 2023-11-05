<?php

namespace App;

use App\DTO\HallDTO;
use Symphograph\Bicycle\DTO\ModelTrait;

class Hall extends HallDTO
{
    use ModelTrait;
    public string $ttt = 'hgh';
}