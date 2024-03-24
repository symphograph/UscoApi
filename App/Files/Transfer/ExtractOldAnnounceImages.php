<?php

namespace App\Files\Transfer;

use App\Announce\Announce;
use App\Announce\AnnounceList;
use Symphograph\Bicycle\Files\FileIMG;
use Symphograph\Bicycle\Files\FileStatus;
use App\Img\Announce\AnnouncePoster;
use App\Img\Announce\AnnounceSketch;

use Symphograph\Bicycle\FileHelper;

class ExtractOldAnnounceImages
{
    /**
     * @var Announce[]
     */
    private array $Announces = [];

    public function __construct()
    {
        $this->initAnnounces();
    }

    private function initAnnounces(): void
    {
        $AnnounceList = AnnounceList::all();
        foreach ($AnnounceList->getList() as $item) {
            $this->Announces[] = Announce::byId($item->id)->initData();
        }
    }

    public function reSaveSketches(): self
    {
        foreach ($this->Announces as $Announce) {
            $this->reSaveSketch($Announce);
        }
        return $this;
    }

    private function reSaveSketch(Announce $Announce): void
    {
        echo 'id:' . $Announce->id . ' - ' . $Announce->progName . '<br>';
        $oldPath = $this->findSketch($Announce->id);

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
            Announce::linkSketch($Announce->id, $FileIMG->id);
        }


        echo '<hr>';
    }

    private function findSketch($id): string|false
    {
        $Sketch = new AnnounceSketch($id);
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

    public function reSavePosters(): self
    {
        foreach ($this->Announces as $Announce) {
            $this->reSavePoster($Announce);
        }
        return $this;
    }

    private function reSavePoster(Announce $Announce): void
    {
        echo 'id:' . $Announce->id . ' - ' . $Announce->progName . '<br>';
        $oldPath = $this->findPoster($Announce->id);

        if (!$oldPath) {
            echo 'Нет файла<hr>';
            return;
        }
        echo $oldPath;
        $ext = pathinfo($oldPath, PATHINFO_EXTENSION);
        $md5 = md5_file($oldPath);
        $FileIMG = FileIMG::newInstance($md5, $ext);

        if (FileHelper::copy($oldPath, $FileIMG->getFullPath())) {
            $FileIMG->status = FileStatus::Uploaded->value;
            $FileIMG->putToDB();
            Announce::linkPoster($Announce->id, $FileIMG->id);
        }


        echo '<hr>';
    }

    private function findPoster($id): string|false
    {
        $Sketch = new AnnouncePoster($id);
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