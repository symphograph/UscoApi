<?php

namespace App\Img\Entry;

use App\Img\PhotoList;

class EntryPhotoList extends PhotoList
{
    const parentFolder = '/img/entry/photo';

    /**
     * @var EntryPhoto[]
     */
    protected array $list = [];

    public function __construct(int $entryId)
    {
        $this->folder = self::parentFolder . '/' . $entryId;
        $baseNames = $this->getOriginBaseNames();
        foreach ($baseNames as $baseName){
            $this->list[] = new EntryPhoto($entryId, $baseName);
        }
    }

    /**
     * @return self[]
     */
    public function getList(): array
    {
        return $this->list;
    }

}