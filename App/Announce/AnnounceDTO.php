<?php

namespace App\Announce;

use Symphograph\Bicycle\DTO\DTOTrait;
use Symphograph\Bicycle\PDO\DB;


class AnnounceDTO implements AnnounceITF
{
    use DTOTrait;

    const string tableName = 'announces';

    public ?int    $id;
    public int     $hallId;
    public string  $progName;
    public ?string $sdescr;
    public string  $description;
    public string  $eventTime;
    public int     $pay;
    public int     $age;
    public ?string $ticketLink;
    public bool    $isShow = false;
    public ?int    $sketchId;
    public ?int    $posterId;
    public string  $verString;


    public static function linkSketch(int $announceId, int $fileId): void
    {
        $sql = "update announces set sketchId = :fileId where id = :announceId";
        $params = compact('fileId', 'announceId');
        DB::qwe($sql, $params);
    }

    public static function linkPoster(int $announceId, int $fileId): void
    {
        $sql = "update announces set posterId = :fileId where id = :announceId";
        $params = compact('fileId', 'announceId');
        DB::qwe($sql, $params);
    }

    public static function unlinkSketch(int $announceId): void
    {
        $sql = "update announces set sketchId = null where id = :announceId";
        $params = compact('announceId');
        DB::qwe($sql, $params);
    }

    public static function unlinkPoster(int $announceId): void
    {
        $sql = "update announces set posterId = null where id = :announceId";
        $params = compact('announceId');
        DB::qwe($sql, $params);
    }

}