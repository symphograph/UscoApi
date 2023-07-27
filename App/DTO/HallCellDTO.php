<?php

namespace App\DTO;

use App\ITF\HallCellITF;

class HallCellDTO extends DTO implements HallCellITF
{
    public int $id;
    public int $col;
    public int $row;
    public bool $exist;
    public string $priceType;

    public static function byArray(array|object $Object): self
    {
        $Object = (object) $Object;
        $cell = new self();
        $cell->bindSelf($Object);
        return $cell;
    }

}