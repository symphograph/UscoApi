<?php

namespace App\Docs;


use Symphograph\Bicycle\DTO\DTOTrait;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\PDO\DB;

class FolderDTO
{
    use DTOTrait;

    const string tableName = 'DocFolders';
    public int $sortPos;
    public bool $isTrash;

    public function __construct(
        public int    $id = 0,
        public string $title = 'ttt'
    )
    {
    }

    public function createSortPos(): void
    {
        $qwe = DB::qwe("select max(sortPos) + 1 as pos from DocFolders");
        $pos = intval($qwe->fetchColumn());
        $this->sortPos = $pos ?: 1;
    }

    protected function setAsTrash(): void
    {
        $this->isTrash = true;
        $this->putToDB();
    }

    protected function resFromTrash(): void
    {
        $this->isTrash = false;
        $this->putToDB();
    }

    public static function posUp(int $id): void
    {
        self::posUpOrDown($id, 'up');
    }

    public static function posDown(int $id): void
    {
        self::posUpOrDown($id, 'down');
    }

    private static function posUpOrDown(int $id, string $direction): void
    {
        $directions = ['up' => -1, 'down' => 1];
        FolderList::defragSortPos();
        $folder = self::byId($id)
            ?: throw new NoContentErr();

        if($direction === 'up' && $folder->sortPos === 1) {
            return;
        }

        $folder->sortPos += $directions[$direction];
        $otherFolder = self::byProp('sortPos', $folder->sortPos);
        if(!$otherFolder) return;

        $otherFolder->sortPos += ($directions[$direction] * -1);
        $folder->putToDB();
        $otherFolder->putToDB();
    }

}