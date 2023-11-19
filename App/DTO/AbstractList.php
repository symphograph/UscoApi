<?php

namespace App\DTO;

abstract class AbstractList implements ListITF
{
    private array $list;

    public function getList(): array
    {
        return $this->list;
    }
}