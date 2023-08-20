<?php

namespace App\DTO;

use App\ITF\AnnounceITF;
use Symphograph\Bicycle\DB;
use Symphograph\Bicycle\Errors\AppErr;

class AnnounceDTO extends DTO implements AnnounceITF
{
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

    public static function byId(int $id): self
    {
        $qwe = qwe("SELECT * from announces where id = :id", ['id' => $id]);
        $qwe = $qwe->fetchObject(self::class)
        or throw new AppErr('Announce ' . $id . ' does not exist', 'Анонс не найден', 404);
        return $qwe;
    }

    protected static function byChild(AnnounceITF $childObject): self
    {
        $objectDTO = new self();
        $objectDTO->bindSelf($childObject);
        return $objectDTO;
    }

    protected function putToDB()
    {
        $params = DB::initParams($this);
        DB::replace('announces', $params);
    }

}