<?php

namespace App\Entry;

use Symphograph\Bicycle\DTO\DTOTrait;

class EntryDTO
{
    use DTOTrait;

    const tableName = 'news';

    public int     $id;
    public string  $title;
    public string  $descr;
    public string  $date;
    public bool    $isShow;
    public ?int    $announceId;
    public ?string $refName;
    public ?string $refLink;
    public string  $markdown;
    public string  $verString;
}