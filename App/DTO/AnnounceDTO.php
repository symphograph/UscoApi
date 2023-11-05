<?php

namespace App\DTO;

use App\ITF\AnnounceITF;
use Symphograph\Bicycle\DTO\DTOTrait;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\PDO\DB;

class AnnounceDTO implements AnnounceITF
{
    use DTOTrait;

    const tableName = 'announces';

    public ?int       $id;
    public ?int       $hallId;
    public ?string    $progName;
    public ?string    $sdescr;
    public ?string    $description;
    public ?string    $img;
    public ?string    $topimg;
    public ?string    $datetime;
    public ?int       $pay;
    public int        $age;
    public ?string    $ticketLink;
    public int|bool   $isShow;
    protected ?string $cache;

}