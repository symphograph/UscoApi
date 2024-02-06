<?php

namespace App\Entry;

use App\Entry\Sections\SectionList;
use Symphograph\Bicycle\DTO\ModelTrait;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\FileHelper;
use Symphograph\Bicycle\PDO\DB;

class Entry extends EntryDTO
{
    use ModelTrait;

    const imgFolder     = '/img/entry/photo';
    const defaultSize = 1080;

    public ?array $parsedMD     = [];
    public array  $Images       = [];
    public array  $usedImages   = [];
    public array  $unusedImages = [];
    
    /**
     * @var Category[]
     */
    public array $categs = [];

    public static function create(): bool|Entry
    {
        $EntryDTO = EntryDTO::byId(1) or
        throw new AppErr('Entry id 1 is empty','Ошибка извлечения');
        unset($EntryDTO->id);
        $EntryDTO->date = date('Y-m-d');
        $EntryDTO->putToDB();

        $id = DB::lastId();
        $Entry = Entry::byId($id) or
        throw new AppErr( 'create Entry error','Ошибка при создании');

        return $Entry;
    }

    public function initData(): void
    {
        if (empty($this->date)) {
            $this->date = date('Y-m-d');
        }

        if (empty($this->verString)) {
            $this->initNewVerString();
            $this->putToDB();
        }

        $this->initCategories();
        $this->initParsedMD();
        $this->initAllImages();
        $this->initUsedImages();
        $this->initUnusedImages();
    }

    public function initNewVerString(): void
    {
        $this->verString = bin2hex(random_bytes(12));
    }

    public function putToDB(): void
    {
        //printr($this);
        qwe("START TRANSACTION");
        $parent = parent::byBind($this);
        $parent->putToDB();


        qwe("DELETE FROM nn_EntryCategs WHERE entry_id = :id", ['id' => $this->id]);

        foreach ($this->categs as $category) {
            if (!$category->checked) {
                continue;
            }
            qwe("
                REPLACE INTO nn_EntryCategs 
                    (entry_id, categ_id) 
                VALUES 
                    (:entryId, :categId)",
                [
                    'entryId' => $this->id,
                    'categId' => $category->id
                ]
            );
        }
        qwe("COMMIT");
    }

    private function initCategories(): void
    {
        $categList = CategList::byEntryId($this->id);
        $this->categs = $categList->getList();
        if (!empty($this->refLink)){
            $this->categs[2]->checked = true;
        }
    }

    private function initParsedMD(): void
    {
        $sectionList = SectionList::byRawContent($this->markdown);
        $this->parsedMD = $sectionList->getList();
    }

    private function initAllImages(): void
    {
        $this->Images = $this->getImgFileNames();
    }

    /**
     * Возвращает FileName[] изображений
     */
    private function getImgFileNames(): array
    {
        $relDir = self::imgFolder . '/' . $this->id . '/' . self::defaultSize;
        $fullDir = FileHelper::fullPath($relDir);
        return FileHelper::FileList($fullDir);
    }

    private function initUsedImages(): void
    {
        $arr = [];
        foreach ($this->parsedMD as $section) {
            if ($section->type === 'img') {
                $arr[] = $section->content;
            }
        }
        $this->usedImages = $arr;
    }

    private function initUnusedImages(): void
    {
        $this->unusedImages = array_diff($this->Images, $this->usedImages);
    }

}