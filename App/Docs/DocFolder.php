<?php

namespace App\Docs;

use Symphograph\Bicycle\DTO\ModelTrait;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\PDO\DB;

class DocFolder extends FolderDTO
{
    use ModelTrait;

    /**
     * @var Doc[]
     */
    public array $docs;

    public static function create(string $title): self|false
    {
        $folder = new self();
        $folder->title = $title;
        $folder->createSortPos();
        $folder->putToDB();
        $folder->id = DB::pdo()->lastInsertId();
        return self::byId($folder->id);
    }

    public function initData(): void
    {
        $this->docs = DocList::folder($this->id)->getList();
    }

    public static function isEmpty(int $id): bool
    {
        $folder = self::byId($id)
            ?: throw new NoContentErr();
        $folder->initData();
        return empty($folder->docs);
    }

    /**
     * @param Doc[] $docs
     */
    public function setDocs(array $docs): void
    {
        $docs = array_filter($docs, fn($el) => $el->folderId === $this->id);
        $this->docs = array_values($docs);
    }

    public function del(): void
    {
        $docList = new DocList($this->docs);
        $docList->setAsTrash();

        self::delById($this->id);
    }

    public function resFromTrash(): void
    {
        parent::resFromTrash();
    }

    public function setAsTrash(): void
    {
        $DocList = new DocList($this->docs);
        $DocList->setAsTrash();
        parent::setAsTrash();
    }

}