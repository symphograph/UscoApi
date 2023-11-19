<?php

namespace App\Entry;

use PDO;

class CategList
{
    /**
     * @var Category[]
     */
    private array $list = [];

    public static function byBind(array $categories): self
    {
        $categList = new self();
        foreach ($categories as $category) {
            $categList->list[] = Category::byBind($category);
        }
        return $categList;
    }

    /**
     * @param int $entryId
     * @return Category[]
     */
    public static function byEntryId(int $entryId): self
    {
        $categList = new self();
        $qwe = qwe("
            select EntryCategs.*, if(nEC.categ_id > 0, 1, 0) as checked
            from EntryCategs 
            left join nn_EntryCategs nEC 
                on EntryCategs.id = nEC.categ_id
                and nEC.entry_id = :entryId",
            ['entryId' => $entryId]
        );
        $categList->list = $qwe->fetchAll(PDO::FETCH_CLASS, Category::class);
        return $categList;
    }

    public function getList(): array
    {
        return $this->list;
    }
}