<?php

namespace App\Entry;

use Symphograph\Bicycle\DTO\AbstractList;

class CategList extends AbstractList
{
    /**
     * @var Category[]
     */
    protected array $list = [];

    public static function getItemClass(): string
    {
        return Category::class;
    }

    public static function byEntryId(int $entryId): static
    {
        $sql = "
            select EntryCategs.*, if(nEC.categ_id > 0, 1, 0) as checked
            from EntryCategs 
            left join nn_EntryCategs nEC 
                on EntryCategs.id = nEC.categ_id
                and nEC.entry_id = :entryId";

        $params = ['entryId' => $entryId];

        return static::bySql($sql, $params);
    }

    /**
     * @return Category[]
     */
    public function getList(): array
    {
        return $this->list;
    }


}