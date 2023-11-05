<?php

namespace App\SDT\Merchandise;

use App\SDT\BindTrait;
use App\SDT\ListTrait;

class MerchandiseList
{
    use BindTrait;
    use ListTrait;

    const tableName = 'merchandise';

    public array $list = [];


    /**
     * @param int $length
     * @return void
     */
    public function initGeneratedList(int $length = 5): void
    {
        $this->list = [];
        for($i = 1; $i <= $length; $i++){
            $merchandise = MerchandiseGenerator::create($i);
            $this->list[] = $merchandise;
        }
    }
}