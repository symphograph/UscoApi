<?php

namespace App\Entry\List;


use App\Entry\EntryDTO;
use Symphograph\Bicycle\DTO\AbstractList;

class EntryList extends AbstractList
{
    /**
     * @var ItemAnnounce[]|ItemExternal[]|ItemDefault[] $list
     */
    protected array $list = [];

    public static function getItemClass(): string
    {
        return EntryDTO::class;
    }

    public static function all(): self
    {
        $sql = "select * from news order by date";
        $EntryList = self::bySql($sql);
        $EntryList->classMap();
        return $EntryList;
    }

    public function classMap(): void
    {
        $list = [];
        foreach ($this->list as $object) {
            $list[] = match (true) {
                !empty($object->announceId) => ItemAnnounce::byBind($object),
                $object->isExternal => ItemExternal::byBind($object),
                default => ItemDefault::byBind($object)
            };
        }
        $this->list = $list;
        $this->initData();
    }

    public function initData(): void
    {
        foreach ($this->list as $object) {
            $object->initData();
        }
    }

    public static function byYear(int $year): self
    {
        $sql = "select * from news where year(date) = :year order by date desc";
        $EntryList = self::bySql($sql, ['year' => $year]);
        $EntryList->classMap();
        return $EntryList;
    }

    public static function byCategory(int $year, string $category): self
    {
        $categories = ['', 'usso', 'euterpe', 'other'];
        $categId = array_search($category, $categories);

        $sql = "
            select news.* 
            from news 
            inner join nn_EntryCategs ec
                on ec.entry_id = news.id
                and ec.categ_id = :categId
            where year(date) = :year 
            order by date desc";

        $EntryList = self::bySql($sql,['categId' => $categId, 'year' => $year]);
        $EntryList->classMap();
        return $EntryList;
    }

    public static function top(): self
    {
        $sql = "select * from news where isShow order by date desc limit 5";
        $EntryList = self::bySql($sql);
        $EntryList->classMap();
        return $EntryList;
    }


    /**
     * @return EntryItem[]
     */
    public function getList(): array
    {
        return $this->list;
    }

}