<?php

namespace App\api;

use PDO;

class PersPlace
{
    public int|null $pers_id;
    public string|null $start;
    public string|null $stop;
    public int|null $place_id;

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
        $qwe = qwe2("SELECT pers_id FROM pers_place group by pers_id");
        return $qwe->fetchAll(PDO::FETCH_COLUMN);
    }

    private static function fixRedundad(self $fPlace) : void
    {
        $nextPlace = self::getNextPlace($fPlace);

        if(!$nextPlace){
            qwe2("UPDATE pers_place 
                SET stop = '2037-12-31' 
                WHERE pers_id = '$fPlace->pers_id' 
                AND start = '$fPlace->start'"
            );
            return;
        }

        if($fPlace->place_id !== $nextPlace->place_id){
            self::fixRedundad($nextPlace);
            return;
        }


        qwe2("
                DELETE FROM pers_place 
                WHERE pers_id = '$nextPlace->pers_id'
                AND start = '$nextPlace->start'"
        );
        qwe2("UPDATE pers_place 
                SET stop = '$nextPlace->stop' 
                WHERE pers_id = '$fPlace->pers_id' 
                AND start = '$fPlace->start'"
        );
        self::fixRedundad($fPlace);
    }

    private static function getFirstPlace(int $pers_id) : self|bool
    {
        $qwe = qwe2("SELECT * FROM pers_place WHERE pers_id = :pers_id order by start LIMIT 1",['pers_id'=> $pers_id]);
        return $qwe->fetchObject(get_class());
    }

    private static function getNextPlace(self $Place) : self|bool
    {
        $qwe = qwe2("
                SELECT * FROM pers_place 
                WHERE pers_id = :pers_id 
                AND start > '$Place->start'
                order by start LIMIT 1",
            ['pers_id'=> $Place->pers_id]
        );
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchObject(get_class());
    }

}