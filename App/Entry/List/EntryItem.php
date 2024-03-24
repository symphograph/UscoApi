<?php

namespace App\Entry\List;

use App\Entry\EntryDTO;
use Symphograph\Bicycle\Files\FileIMG;

abstract class EntryItem implements EntryItemITF
{
    public int     $id;
    public string  $title;
    public string  $descr;
    public string  $date;
    public string  $isShow;
    public ?string $refName;
    public bool $isExternal;
    public ?string $refLink;
    public ?string $verString;
    public ?int $sketchId;
    public FileIMG $sketch;


    public function initData(): void
    {
        if(empty($this->verString)) {
            $this->initVerString();
        }
        $this->initSketch();
    }

    private function initSketch(): void
    {
        if(empty($this->sketchId)) {
            return;
        }
        $sketch = FileIMG::byId($this->sketchId);
        if($sketch) {
            $this->sketch = $sketch;
        }
    }

    private function initVerString(): void
    {
        $this->verString = bin2hex(random_bytes(12));
        $Entry = EntryDTO::byId($this->id);
        $Entry->verString = $this->verString;
        $Entry->putToDB();
    }
}