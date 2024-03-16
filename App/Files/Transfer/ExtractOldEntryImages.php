<?php

namespace App\Files\Transfer;

use App\Entry\Entry;
use App\Entry\List\EntryList;
use App\Files\FileIMG;
use App\Img\Entry\EntrySketch;
use App\Img\Sketch;
use Symphograph\Bicycle\FileHelper;

class ExtractOldEntryImages
{
    /**
     * @var Entry[]
     */
    private array $Entries = [];

    public function __construct()
    {
        $this->initEntries();
    }

    private function initEntries(): void
    {
        $EntryList = EntryList::all();
        foreach ($EntryList->getList() as $item) {
            $this->Entries[] = Entry::byId($item->id)->initData();
        }
    }

    public function reSaveAllPhotos(): self
    {
        foreach ($this->Entries as $Entry) {
            if (empty($Entry->Images)) {
                continue;
            }
            $this->reSavePhotos($Entry);
        }
        return $this;
    }

    private function reSavePhotos(Entry $Entry): void
    {
        $dir = $Entry->getPhotosDir();

        foreach ($Entry->Images as $baseName) {
            $FileIMG = FileIMG::byNameWithMD5($baseName);

            $oldPath = $dir . '/' . $baseName;

            if (!FileHelper::fileExists($oldPath)) {
                continue;
            }

            if (FileHelper::copy($oldPath, $FileIMG->getFullPath())) {
                $FileIMG->putToDB();
                Entry::linkPhoto($Entry->id, $FileIMG->id);
            }
        }
    }

    public function reSaveSketches(): self
    {
        foreach ($this->Entries as $Entry) {
            $this->reSaveSketch($Entry);
        }
        return $this;
    }

    private function reSaveSketch(Entry $Entry): void
    {
        echo 'id:' . $Entry->id . ' - ' . $Entry->title . '<br>';
        $oldPath = $this->findSketch($Entry->id);

        if (!$oldPath) {
            echo 'Нет файла<hr>';
            return;
        }
        echo $oldPath;
        $ext = pathinfo($oldPath, PATHINFO_EXTENSION);
        $md5 = md5_file($oldPath);
        $FileIMG = FileIMG::newInstance($md5, $ext);

        if (FileHelper::copy($oldPath, $FileIMG->getFullPath())) {
            $FileIMG->putToDB();
            Entry::linkSketch($Entry->id, $FileIMG->id);
        }


        echo '<hr>';
    }

    private function findSketch($entryId): string|false
    {
        $Sketch = new EntrySketch($entryId);
        $exts = ['jpg', 'jpeg', 'png', 'svg'];
        $EXTS = array_map('strtoupper', $exts);
        $exts = array_merge($exts, $EXTS);

        foreach ($exts as $ext) {
            $fullPath = $Sketch->getOriginFullPath($ext);
            if (FileHelper::fileExists($fullPath)) {
                return $fullPath;
            }
        }
        return false;
    }
}