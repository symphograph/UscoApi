<?php

namespace App\Entry\List;

use Symphograph\Bicycle\DTO\BindTrait;

class ItemAnnounce extends EntryItem
{
    use BindTrait;

    public ?int $announceId;

    function initData(): void
    {
        parent::initData();

        $this->refLink = '/announce/' . $this->announceId;
    }
}