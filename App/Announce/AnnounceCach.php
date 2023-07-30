<?php

namespace App\Announce;

use PDO;

class AnnounceCach
{
    public int $id;
    public string $datetime;
    public ?string $cache;

    /**
     * @return self[]
     */
    public static function futureCacheList(): array
    {
        $qwe = qwe("
            SELECT id, datetime, cache 
            from announces 
            where datetime >= now() order by datetime"
        );
        return $qwe->fetchAll(PDO::FETCH_CLASS, self::class) ?? [];
    }

    /**
     * @return self[]
     */
    public static function allCacheList(): array
    {
        $qwe = qwe("
            SELECT id, datetime, cache 
            from announces 
            order by datetime desc"
        );
        return $qwe->fetchAll(PDO::FETCH_CLASS, self::class) ?? [];
    }

    /**
     * @return self[]
     */
    public static function hallCacheList(int $hallId): array
    {
        $qwe = qwe("
            SELECT id, datetime, cache 
            from announces 
            where hallId = :hallId order by datetime desc",
        ['hallId' => $hallId]
        );
        return $qwe->fetchAll(PDO::FETCH_CLASS, self::class) ?? [];
    }

    /**
     * @return self[]
     */
    public static function yearCacheList(int $year): array
    {
        $qwe = qwe("
            SELECT id, datetime, cache 
            from announces 
            where year(datetime) = :year order by datetime desc",
            ['year' => $year]
        );
        return $qwe->fetchAll(PDO::FETCH_CLASS, self::class) ?? [];
    }

}