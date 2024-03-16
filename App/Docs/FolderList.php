<?php

namespace App\Docs;


use Symphograph\Bicycle\DTO\AbstractList;
use Symphograph\Bicycle\PDO\DB;

class FolderList extends AbstractList
{

    /**
     * @var DocFolder[]
     */
    public array $list = [];

    public static function getItemClass(): string
    {
        return DocFolder::class;
    }

    public static function allPublic(): self
    {
        $sql = "select * from DocFolders where !isTrash order by sortPos";
        return self::bySql($sql);
    }

    public static function all(): self
    {
        $sql = "select * from DocFolders order by sortPos";
        return self::bySql($sql);
    }

    public static function delEmptyTrash(): void
    {
        $emptyFolders = self::emptyTrash()->list;
        foreach ($emptyFolders as $folder) {
            DocFolder::delById($folder->id);
        }
        self::defragSortPos();
    }

    private static function emptyTrash(): self
    {
        $sql = "select df.*
            from DocFolders df
            left join Docs d on df.id = d.folderId
            where d.id is null
            and df.isTrash";
        return self::bySql($sql);
    }

    public function initData(): void
    {
        $docList = DocList::all();

        foreach ($this->list as $folder) {
            $folder->setDocs($docList->list);
        }
    }

    public static function defragSortPos(): void
    {
        $pdo = DB::pdo();
        $pdo->exec("SET @pos := 0");
        $pdo->exec("UPDATE DocFolders 
            SET sortPos = (@pos := @pos + 1)
            ORDER BY sortPos");
    }
}