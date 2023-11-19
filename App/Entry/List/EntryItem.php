<?php

namespace App\Entry\List;

use App\Entry\EntryDTO;

abstract class EntryItem implements EntryItemITF
{


    public int     $id;
    public string  $title;
    public string  $descr;
    public string  $date;
    public string  $isShow;
    public ?string $refName;
    public ?string $refLink;
    public ?string $verString;


    public function initData(): void
    {
        if(empty($this->verString)) {
            $this->initVerString();
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