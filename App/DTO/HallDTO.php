<?php

namespace App\DTO;

use App\ITF\AnnounceITF;
use App\ITF\HallITF;
use PDO;

class HallDTO extends DTO implements HallITF
{
    public int    $id;
    public string $name;
    public string $map;

    public static function byId(int $id): self
    {
        $qwe = qwe("select * from halls where id = :id", ['id' => $id]);
        return $qwe->fetchObject(self::class);
    }

    protected static function byChild(AnnounceITF $childObject): self
    {
        $objectDTO = new self();
        $objectDTO->bindSelf($childObject);
        return $objectDTO;
    }

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