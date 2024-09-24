<?php

namespace App\Yandex\Geo;

use Symphograph\Bicycle\DTO\DTOTrait;
use Symphograph\Bicycle\PDO\DB;

class YaSuggestDTO {

    use DTOTrait;
    const string tableName = "yaSuggest";

    public int    $id;
    public string $title;
    public string $subtitle;
    public string $uri;
    public string $addressMD5;
    public ?int   $oid;


    public static function byUnique(string $subtitle, string $addressMD5): static|false
    {
        $sql = "select * from yaSuggest where subtitle = :subtitle and addressMD5 = :addressMD5";
        $params = compact("subtitle", "addressMD5");
        return DB::qwe($sql, $params)->fetchObject(static::class) ?? false;
    }

    protected function initOID(): static
    {
        $parsedUrl = parse_url($this->uri);
        if (empty($parsedUrl['query'])) {
            return $this;
        }

        parse_str($parsedUrl['query'], $queryParams);
        if (empty($queryParams['oid'])) {
            return $this;
        }

        $this->oid = (int)$queryParams['oid'];
        return $this;
    }

    protected function afterPut(): void
    {
        if (empty($this->id)){
            $this->id = DB::lastId();
        }
        if (empty($this->id)){
            $self = self::byUnique($this->subtitle, $this->addressMD5);
            $this->id = $self->id;
        }
    }

}