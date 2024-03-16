<?php

namespace App\Entry;

use Symphograph\Bicycle\DTO\DTOTrait;
use Symphograph\Bicycle\PDO\DB;

class EntryDTO
{
    use DTOTrait;

    const tableName = 'news';

    public int     $id;
    public string  $title;
    public string  $descr;
    public string  $date;
    public bool    $isShow;
    public ?int    $announceId;
    public ?string $refName;
    public ?string $refLink;
    public bool $isExternal;
    public string  $markdown;
    public string  $verString;
    public ?int    $sketchId;

    public static function linkPhoto(int $entryId, int $imgId): void
    {
        $sql = "
            replace into nn_EntryPhoto 
                (entryId, imgId) 
            values 
                (:entryId, :imgId)";
        $params = compact('entryId', 'imgId');
        DB::qwe($sql, $params);
    }

    public static function unlinkPhoto(int $entryId, int $imgId): void
    {
        $sql = "delete from nn_EntryPhoto where entryId = :entryId and imgId = :imgId";
        $params = compact('entryId', 'imgId');
        DB::qwe($sql, $params);
    }

    public static function unlinkAllPhotos(int $entryId): void
    {
        $sql = "delete from nn_EntryPhoto where entryId = :entryId";
        $params = compact('entryId');
        DB::qwe($sql, $params);
    }

    public static function linkSketch(int $entryId, int $fileId): void
    {
        $sql = "update news set sketchId = :fileId where id = :entryId";
        $params = compact('fileId', 'entryId');
        DB::qwe($sql, $params);
    }

    public static function unlinkSketch(int $entryId): void
    {
        $sql = "update news set sketchId = null where id = :entryId";
        $params = compact('entryId');
        DB::qwe($sql, $params);
    }

}