<?php

namespace App\SDT\Merchandise;

use App\SDT\BindTrait;

class Merchandise
{
    use BindTrait;
    const tableName = 'merchandise';

    protected int $id;
    protected string $name;
    protected float $price;


}