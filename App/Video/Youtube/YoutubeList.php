<?php

namespace App\Video\Youtube;

use Symphograph\Bicycle\DTO\AbstractList;

class YoutubeList extends AbstractList
{
    /**
     * @var Youtube[]
     */
    protected array $list = [];

    public static function getItemClass(): string
    {
        return Youtube::class;
    }

    public static function someLast(int $limit): static
    {
        $sql = "
            SELECT * FROM video 
            where isShow 
            order by video.createdAt desc 
            limit :limit";

        return static::bySql($sql, ["limit" => $limit]);
    }

    public static function all(): static
    {
        $sql = "SELECT * FROM video";
        return static::bySql($sql);
    }

    public static function allPublic(): static
    {
        $sql = "SELECT * FROM video where isShow order by createdAt desc";
        return static::bySql($sql);
    }



    /**
     * @return Youtube[]
     */
    public function getList(): array
    {
        return $this->list;
    }
}