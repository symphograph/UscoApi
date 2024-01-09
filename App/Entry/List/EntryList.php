<?php

namespace App\Entry\List;

use App\Entry\EntryDTO;
use PDO;

class EntryList
{
    /**
     * @param ItemAnnounce[]|ItemExternal[]|ItemDefault[] $list
     */
    public function __construct(private array $list = [])
    {
    }

    public static function all(): self
    {
        $EntryList = new self();
        $qwe = qwe("select * from news order by date");

        $EntryList->list = $qwe->fetchAll(PDO::FETCH_CLASS, EntryDTO::class);
        $EntryList->classMap();
        return $EntryList;
    }

    public static function byYear(int $year): self
    {
        $EntryList = new self();
        $qwe = qwe("select * from news where year(date) = :year order by date desc", ['year' => $year]);

        $EntryList->list = $qwe->fetchAll(PDO::FETCH_CLASS, EntryDTO::class);
        $EntryList->classMap();
        return $EntryList;
    }

    public static function byCategory(int $year, string $category): self
    {
        $categories = ['', 'usso', 'euterpe', 'other'];
        $categId = array_search($category, $categories);
        $EntryList = new self();
        $qwe = qwe("
            select news.* 
            from news 
            inner join nn_EntryCategs ec
                on ec.entry_id = news.id
                and ec.categ_id = :categId
            where year(date) = :year 
            order by date desc",
            ['categId' => $categId,'year' => $year]
        );

        $EntryList->list = $qwe->fetchAll(PDO::FETCH_CLASS, EntryDTO::class);
        $EntryList->classMap();
        return $EntryList;
    }

    public function classMap(): void
    {
        $list = [];
        foreach ($this->list as $object) {
            $list[] = match (true) {
                !empty($object->announceId) => ItemAnnounce::byBind($object),
                !empty($object->refLink) => ItemExternal::byBind($object),
                default => ItemDefault::byBind($object)
            };
        }
        $this->list = $list;
        $this->initData();
    }

    public static function top(): self
    {
        $EntryList = new self();
        $qwe = qwe("select * from news where isShow order by date desc limit 5");

        $EntryList->list = $qwe->fetchAll(PDO::FETCH_CLASS, EntryDTO::class);
        $EntryList->classMap();
        return $EntryList;
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function initData(): void
    {
        foreach ($this->list as $object) {
            $object->initData();
        }
    }

}