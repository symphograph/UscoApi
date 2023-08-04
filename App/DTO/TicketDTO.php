<?php

namespace App\DTO;

use App\ITF\AnnounceITF;
use App\ITF\TicketITF;
use Symphograph\Bicycle\DB;

class TicketDTO extends DTO implements TicketITF
{
    public int    $id;
    public int    $announceId;
    public ?int   $userId;
    public ?string $reservedAt;
    public int    $cellId;
    public bool   $offline;
    public int    $seatRow;
    public int    $seatNum;
    public string $priceType;

    public static function byId(int $id): self|false
    {
        $qwe = qwe("
            select * from tickets 
            where id = :id",
            ['id' => $id]
        );
        return $qwe->fetchObject(self::class);
    }

    protected static function byChild(TicketITF $childObject): self
    {
        $objectDTO = new self();
        $objectDTO->bindSelf($childObject);
        return $objectDTO;
    }

    public function putToDB(): void
    {
        $params = DB::initParams($this);
        DB::replace('tickets', $params);
    }

}