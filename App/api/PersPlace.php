<?php

namespace App\api;

use PDO;

class PersPlace
{
    public int|null $persId;
    public string|null $start;
    public string|null $stop;
    public int|null $placeId;

    public static function runPlaceFixer(): void
    {
        $perses = self::getPerses();
        if(!$perses) return;
        foreach ($perses as $pers)
        {
            $fPlace = self::getFirstPlace($pers);
            self::fixRedundad($fPlace);
        }
    }

    private static function getPerses(): bool|array
    {
        $qwe = qwe2("SELECT persId FROM pers_place group by persId");
        return $qwe->fetchAll(PDO::FETCH_COLUMN);
    }

    private static function fixRedundad(self $fPlace) : void
    {
        $nextPlace = self::getNextPlace($fPlace);

        if(!$nextPlace){
            qwe2("UPDATE pers_place 
                SET stop = '2037-12-31' 
                WHERE persId = '$fPlace->persId' 
                AND start = '$fPlace->start'"
            );
            return;
        }

        if($fPlace->placeId !== $nextPlace->placeId){
            self::fixRedundad($nextPlace);
            return;
        }


        qwe2("
                DELETE FROM pers_place 
                WHERE persId = '$nextPlace->persId'
                AND start = '$nextPlace->start'"
        );
        qwe2("UPDATE pers_place 
                SET stop = '$nextPlace->stop' 
                WHERE persId = '$fPlace->persId' 
                AND start = '$fPlace->start'"
        );
        self::fixRedundad($fPlace);
    }

    private static function getFirstPlace(int $persId) : self|bool
    {
        $qwe = qwe2("SELECT * FROM pers_place WHERE persId = :persId order by start LIMIT 1",['persId'=> $persId]);
        return $qwe->fetchObject(get_class());
    }

    private static function getNextPlace(self $Place) : self|bool
    {
        $qwe = qwe2("
                SELECT * FROM pers_place 
                WHERE persId = :persId 
                AND start > '$Place->start'
                order by start LIMIT 1",
            ['persId'=> $Place->persId]
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchObject(get_class());
    }

}