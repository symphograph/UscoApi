<?php

namespace App\Yandex\Geo;

use Symphograph\Bicycle\DTO\ModelTrait;

class YaSuggest extends YaSuggestDTO
{
    use ModelTrait;
    public YaAddress $address;

    public static function newInstance(string $title, string $subtitle, string $uri, YaAddress $address): static
    {
        $Suggest = new static();
        $Suggest->title = $title;
        $Suggest->subtitle = $subtitle;
        $Suggest->uri = $uri;
        $Suggest->address = $address;
        $Suggest->initOID();
        return $Suggest;
    }

    public function initYaAddress(): static
    {
        $this->address = YaAddress::byMD5($this->addressMD5);
        return $this;
    }

    private function beforePut(): void
    {
        $this->address->putToDB();
        $this->addressMD5 = $this->address->md5;
    }

}