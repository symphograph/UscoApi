<?php

namespace App\Yandex\Geo;

use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Helpers\Str;

class YaGeoSuggestComponent {

    public string $name;
    public string $kind;

    public function __construct(array $component)
    {
        $this->name = $component['name'] ?? '';
        $kinds = $component['kind'] ?? [];
        $kind = $kinds[0] ?? throw new AppErr('kind is empty');

        $this->kind = Str::camel(strtolower($kind));
    }
}