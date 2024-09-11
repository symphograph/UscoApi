<?php

namespace App\Hall;

use App\Yandex\Geo\YaSuggest;
use Symphograph\Bicycle\DTO\ModelTrait;

class Hall extends HallDTO
{
    use ModelTrait;

    public YaSuggest $suggest;

    public function initData(): static
    {
        $this->initSuggest();
        return $this;
    }

    public function initSuggest(): static
    {
        $suggest = YaSuggest::byId($this->suggestId);
        if($suggest) $this->suggest = $suggest->initYaAddress();

        return $this;
    }

}