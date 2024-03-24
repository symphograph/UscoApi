<?php

namespace App\Files;

class ImgList extends \Symphograph\Bicycle\Files\ImgList
{
    public static function byEntry(int $entryId): self
    {
        $sql = "
            select Files.* from Files 
            inner join nn_EntryPhoto nEP 
                on Files.id = nEP.imgId
                and nEP.entryId = :entryId";
        $params = compact('entryId');
        return self::bySql($sql, $params);
    }
}