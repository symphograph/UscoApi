<?php

namespace App\DTO;

use App\ITF\HallITF;
use PDO;
use Symphograph\Bicycle\DTO\DTOTrait;

class HallDTO implements HallITF
{
    use DTOTrait;
    const tableName = 'halls';

    public int    $id;
    public string $name;
    public string $map;
    public ?string $address;

    /**
     * @return array<self>
     */
    public static function getList(): array
    {
        $qwe = qwe("SELECT * FROM halls");
        if(!$qwe or !$qwe->rowCount()){
            return [];
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS,self::class);
    }
}