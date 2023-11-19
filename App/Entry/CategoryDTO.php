<?php

namespace App\Entry;

use Symphograph\Bicycle\DTO\DTOTrait;

class CategoryDTO
{
    use DTOTrait;

    public int $id;
    public string $label;
    public string $caption;
    public ?string $descr;
    public string $sname;
}