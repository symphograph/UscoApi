<?php

namespace App\DTO;

use App\ITF\HallPlanITF;
use Symphograph\Bicycle\DTO\DTOTrait;

class HallPlanDTO implements HallPlanITF
{
    use DTOTrait;
    const string tableName = 'hallPlans';

    public int          $id;
    public array|string $pricing;
    public array|string $cells;
    public array|string $structure;

}