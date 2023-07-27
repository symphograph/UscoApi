<?php

namespace App\DTO;

use App\ITF\AnnounceITF;
use Symphograph\Bicycle\Errors\AppErr;

class AnnounceDTO extends DTO implements AnnounceITF
{
    public ?int       $id;
    public ?int       $hallId;
    public ?string    $prog_name;
    public ?string    $sdescr;
    public ?string    $description;
    public ?string    $img;
    public ?string    $topimg;
    public ?string    $datetime;
    public ?int       $pay;
    public int        $age;
    public ?string    $ticket_link;
    public int|bool   $isShow;
    protected ?string $cache;

    public static function byId(int $id): self
    {
        $qwe = qwe("SELECT * from announces where id = :id", ['id' => $id])
        or throw new AppErr('Announce ' . $id . ' does not exist');
        return $qwe->fetchObject(self::class);
    }

    protected static function byChild(AnnounceITF $childObject): self
    {
        $objectDTO = new self();
        $objectDTO->bindSelf($childObject);
        return $objectDTO;
    }


}