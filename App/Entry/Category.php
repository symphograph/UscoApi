<?php

namespace App\Entry;

use Symphograph\Bicycle\DTO\ModelTrait;

class Category extends CategoryDTO
{
    use ModelTrait;

    public bool $checked;

}