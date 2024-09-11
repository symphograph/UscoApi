<?php

namespace App\Hall;

use Symphograph\Bicycle\DTO\AbstractList;

class HallList extends AbstractList
{

    /**
     * @var Hall[]
     */
    protected array $list = [];

    public static function getItemClass() : string
    {
        return Hall::class;
    }

    public static function all(): static
    {
        $sql = "select * from halls";
        return static::bySql($sql);
    }

    /**
     * @return Hall[]
     */
    public function getList() : array
    {
        return $this->list;
    }
}