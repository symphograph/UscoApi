<?php

namespace App\Announce;

use PDO;

class AnnounceList
{

    /**
     * @param Announce[] $list
     */
    public function __construct(private array $list = []){}

    public static function byYear(int $year): self
    {
        $AnnounceList = new self();
        $qwe = qwe("
            SELECT *
            from announces
            where year(eventTime) = :year 
            order by eventTime desc",
            ['year' => $year]
        );
        $AnnounceList->list = $qwe->fetchAll(PDO::FETCH_CLASS, Announce::class) ?? [];
        return $AnnounceList;
    }

    public static function byHall(int $hallId): self
    {
        $AnnounceList = new self();
        $qwe = qwe("
            SELECT *
            from announces
            where hallId = :hallId
            order by eventTime desc",
            ['hallId' => $hallId]
        );
        $AnnounceList->list = $qwe->fetchAll(PDO::FETCH_CLASS, Announce::class);
        return $AnnounceList;
    }

    public static function byFuture(): self
    {
        $AnnounceList = new self();
        $qwe = qwe("
            SELECT *
            from announces
            where eventTime >= now() 
            order by eventTime"
        );
        $AnnounceList->list = $qwe->fetchAll(PDO::FETCH_CLASS, Announce::class);
        return $AnnounceList;
    }

    public function initData(): void
    {
        foreach ($this->list as $object) {
            $object->initData();
        }
    }

    public function getList(): array
    {
        return $this->list;
    }
}