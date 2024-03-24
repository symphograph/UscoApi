<?php

namespace App\Docs;


use Symphograph\Bicycle\Files\FileDoc;
use Symphograph\Bicycle\Files\FileStatus;
use Symphograph\Bicycle\DTO\ModelTrait;
use Symphograph\Bicycle\FileHelper;
use Symphograph\Bicycle\Helpers\DateTimeHelper;
use Symphograph\Bicycle\PDO\DB;


class Doc extends DocDTO
{
    use ModelTrait;

    const string prefix = 'USSO';

    public FileDoc $file;

    public static function byId(int $id): self|false
    {
        $sql = "select * from Files inner join Docs on Docs.id = Files.id and Files.id = :id";
        $params = compact('id');
        $qwe = DB::qwe($sql, $params);
        $data = $qwe->fetchObject();
        if (empty($data)) return false;
        return self::byJoin($data);
    }

    public static function byJoin(object|array $data): self
    {
        $doc = self::byBind($data);
        $doc->file = FileDoc::byBind($data);
        return $doc;
    }

    public function makePublic(): void
    {
        $sourcePath = $this->file->getFullPath();
        $publicPath = $this->file->getPubFullPath($this->fileName);
        FileHelper::copy($sourcePath, $publicPath);
        $this->file->updateStatus(FileStatus::Public);
    }

    protected function beforeDel(): void
    {
        $pubPath = $this->file->getPubFullPath($this->fileName);
        FileHelper::delete($pubPath);
    }

    protected function afterDel(): void
    {
        $this->file->del();
    }

    private function beforePut(): void
    {
        $this->file->putToDB();
        $this->id = $this->file->id;
        $this->initFileName($this->file->ext);
    }

    public function initFileName(string $ext): void
    {
        $this->fileName = $this->getFileName($ext);
    }

    public function getFileName(string $ext): string
    {
        $title = $this->title;
        $atDate = $this->atDate;

        $extractedDate = DateTimeHelper::extractDate($title);
        if ($extractedDate) {
            $fileName = str_replace($extractedDate, '', $title);
        } else {
            $fileName = $title;
        }

        $fileName = FileHelper::renameToTranslit($fileName);

        return self::prefix . $atDate . '~' . $fileName . '~id' . $this->id . '.' . $ext;
    }
}