<?php

namespace App\Docs;

use Symphograph\Bicycle\DTO\AbstractList;

class DocList extends AbstractList
{

    /**
     * @var Doc[] $list
     */
    public array $list = [];

    public static function getItemClass(): string
    {
        return Doc::class; // Возвращаем класс элементов списка
    }

    public static function all(): self
    {
        $sql = "
            select * from Files 
            inner join Docs 
                on Docs.id = Files.id 
                and !Docs.isTrash
            order by atDate, title";
        return self::byJoinSql($sql);
    }

    public static function folder(int $folderId): self
    {
        $sql = "
            select * from Files 
            inner join Docs 
                on Docs.id = Files.id
            where Docs.folderId = :folderId 
            order by atDate, title";
        $params = compact('folderId');
        return self::byJoinSql($sql, $params);
    }

    public static function trash(): self
    {
        $sql = "
            select * from Files 
            inner join Docs 
                on Docs.id = Files.id
            where Docs.isTrash = 1
            order by atDate, title";
        return self::byJoinSql($sql);
    }

    public function moveToFolder(int $folderId): void
    {
        foreach ($this->list as $doc) {
            $doc->moveToFolder($folderId);
        }
    }

    public function del(): void
    {
        foreach ($this->list as $doc) {
            $doc->del();
        }
        //FolderList::delEmptyTrash();
    }

    public function setAsTrash(): void
    {
        foreach ($this->list as $doc) {
            $doc->setAsTrash();
        }
    }

    public function resFromTrash(): void
    {
        foreach ($this->list as $doc) {
            $doc->resFromTrash();
        }
    }

    public function makePublic() : void
    {
        foreach ($this->list as $doc){
            $doc->makePublic();
        }
    }
}