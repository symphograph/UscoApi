<?php

namespace App\Entry;

use App\Entry\CategoryDTO;
use PDO;
use Symphograph\Bicycle\DTO\ModelTrait;

class Category extends CategoryDTO
{
    use ModelTrait;

    public bool $checked;

}