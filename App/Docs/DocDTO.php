<?php

namespace App\Docs;

use Symphograph\Bicycle\DTO\DTOTrait;
use Symphograph\Bicycle\PDO\DB;

class DocDTO
{
    use DTOTrait;

    const string tableName = 'Docs';

    public int    $id;
    public int    $folderId;
    public string $fileName;
    public string $title;
    public string $atDate;
    public bool   $isTrash;

    public function setAsTrash(): void
    {
        $this->isTrash = true;
        $sql = "update Docs set isTrash = 1 where id = :id";
        $params = ['id' => $this->id];
        DB::qwe($sql, $params);
    }

    public function resFromTrash(): void
    {
        $this->isTrash = false;
        $this->putToDB();
        $folder = DocFolder::byId($this->folderId);
        $folder->resFromTrash();
    }

    public static function newInstance(int $folderId, string $title, string $atDate): static
    {
        $props = get_defined_vars();
        $hackIDENotice = func_num_args();

        return static::byBind($props);
    }

    public function moveToFolder(int $folderId): void
    {
        $this->folderId = $folderId;

        $sql = "update Docs set folderId = :folderId where id = :id";
        $params = ['folderId' => $folderId, 'id' => $this->id];
        DB::qwe($sql, $params);
    }

    protected function beforeDel() : void
    {

    }

    protected function afterDel(): void
    {

    }
}