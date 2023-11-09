<?php

namespace App\Announce;

use Symphograph\Bicycle\DTO\DTOTrait;


class AnnounceDTO implements AnnounceITF
{
    use DTOTrait;

    const tableName = 'announces';

    public ?int    $id;
    public int     $hallId;
    public string  $progName;
    public ?string $sdescr;
    public string  $description;
    public string  $eventTime;
    public int     $pay;
    public int     $age;
    public ?string $ticketLink;
    public bool    $isShow = false;
    public string  $verString;

}