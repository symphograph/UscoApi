<?php

namespace App\Yandex\Geo;

class YaGeoSuggestResult {

    public string $title;
    public string $subtitle;
    public array  $tags;
    public float  $distance;
    public string $formattedAddress;
    public string $uri;

    /**
     * @var YaGeoSuggestComponent[]
     */
    private array $components = [];

    public function __construct(array $result)
    {
        $this->title = $result['title']['text'] ?? '';
        $this->subtitle = $result['subtitle']['text'] ?? '';
        $this->tags = $result['tags'] ?? [];
        $this->distance = $result['distance']['value'] ?? 0.0;
        $this->formattedAddress = $result['address']['formatted_address'] ?? '';
        $this->uri = $result['uri'] ?? '';

        $this->components = array_map(
            fn($component) => new YaGeoSuggestComponent($component),
                $result['address']['component'] ?? []
        );
    }

    public function getSuggest(): YaSuggest
    {
        return YaSuggest::newInstance(
            $this->title,
            $this->subtitle,
            $this->uri,
            $this->getYaAddress()
        );
    }

    private function getYaAddress(): YaAddress
    {
        $address = YaAddress::byComponents($this->components);
        $address->formatted = $this->formattedAddress;
        return $address;
    }


}