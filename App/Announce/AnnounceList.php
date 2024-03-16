<?php

namespace App\Announce;

use Symphograph\Bicycle\DTO\AbstractList;

class AnnounceList extends AbstractList
{
    /**
     * @var Announce[] $list
     */
    protected array $list = [];

    public static function getItemClass(): string
    {
        return Announce::class;
    }

    public static function all(): self
    {
        $sql = "
            SELECT * from announces
            order by eventTime";

        return self::bySql($sql);
    }

    public static function byYear(int $year): self
    {
        $sql = "
            SELECT *
            from announces
            where year(eventTime) = :year 
            order by eventTime desc";

        $params = compact('year');
        return self::bySql($sql,$params);
    }

    public static function byHall(int $hallId): self
    {
        $sql = "
            SELECT *
            from announces
            where hallId = :hallId
            order by eventTime desc";

        $params = compact('hallId');

        return self::bySql($sql, $params);
    }

    public static function byFuture(): self
    {
        $sql = "
            SELECT *
            from announces
            where eventTime >= now() 
            order by eventTime";

        return self::bySql($sql);
    }

}