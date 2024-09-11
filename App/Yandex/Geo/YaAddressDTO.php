<?php

namespace App\Yandex\Geo;

use Symphograph\Bicycle\DTO\DTOTrait;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\PDO\DB;

class YaAddressDTO
{
    use DTOTrait;

    const string tableName = "yaAddress";

    public string $md5;
    public string $formatted;
    public string $createdAt;
    public string $updatedAt;

    protected ?string $country; // страна
    protected ?string $region; // регион
    protected ?string $province; // область;
    protected ?string $area; // район обласи, городской совет
    protected ?string $locality; // населенный пункт
    protected ?string $district; // район, микрорайон, квартал города, посёлок
    protected ?string $street; // улица
    protected ?string $house; // дом
    protected ?string $hydro; // река, озеро, ручей, водохранилище и т.п.
    protected ?string $station; // остановка
    protected ?string $metroStation; // станция метро
    protected ?string $railwayStation; // железнодорожная станция
    protected ?string $route; // линия метро, шоссе, ж.д. линия
    protected ?string $vegetation; // лес, парк, сад и т.п.
    protected ?string $airport; // аэропорт
    protected ?string $other; // прочее
    protected ?string $entrance; // вход
    protected ?string $level; // этаж
    protected ?string $apartment; // квартира
    protected ?string $unknown; // ничего из перечисленного

    public static function byId(int $id): static|false
    {
        throw new AppErr('byId is not implemented. Use md5 instead.');
    }

    

    /**
     * @param YaGeoSuggestComponent[] $components
     */
    public static function byComponents(array $components): static
    {
        $address = new static();
        foreach ($components as $component) {
            if (isset($address->{$component->kind})) continue;
            $address->{$component->kind} = $component->name;
        }
        return $address;
    }

    public static function byMD5(string $md5): static|false
    {
        $sql = "SELECT * FROM yaAddress WHERE md5 = :md5";
        return DB::qwe($sql, ["md5" => $md5])->fetchObject(static::class) ?? false;
    }

    public function createMd5(): string
    {
        $props = $this->getDtoProps();
        unset($props['md5'], $props['formatted'], $props['createdAt'],$props['updatedAt']);
        $imploded = implode('', $props);
        return md5($imploded);
    }

    protected function beforePut(): void
    {
        $this->updatedAt = date("Y-m-d H:i:s");
        $this->md5 = $this->createMd5();
    }

}