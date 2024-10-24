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
            order by eventTime desc";

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

    public static function byMonth(int $year, int $month): self
    {
        $sql = "
            SELECT *
            from announces
            where year(eventTime) = :year 
                and month(eventTime) = :month
            order by eventTime desc";

        $params = compact('year', 'month');
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

    public static function byDate(string $date): self
    {
        $sql = "
            SELECT *
            from announces
            where date(eventTime) = :date 
            order by eventTime desc";

        $params = compact('date');
        return self::bySql($sql,$params);
    }

    public static function byFuture(string $date, bool $desc = false): self
    {
        $sql = "
            SELECT *
            from announces
            where date(eventTime) >= :date 
            order by eventTime";
        if($desc) $sql .= ' desc';
        $params = compact('date');
        return self::bySql($sql, $params);
    }


    /**
     * @return Announce[]
     */
    public function getList(): array
    {
        return $this->list;
    }

}