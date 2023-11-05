<?php

namespace App\SDT\Merchandise;

class MerchandiseGenerator extends Merchandise
{
    const minPrice = 10.0;
    const maxPrice = 100.0;

    public function __construct(int $i)
    {
        $this->id = $i;
        $this->name = "Товар $i";
        $this->price = self::createPrice(self::minPrice, self::maxPrice);
    }

    public static function create(int $i): parent
    {
        $data = new self($i);
        return Merchandise::byBind($data);
    }

    private static function createPrice(float $min = 10.0, float $max = 100.0): float
    {
        $price = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return round($price, 2);
    }
}