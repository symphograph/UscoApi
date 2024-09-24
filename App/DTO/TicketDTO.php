<?php

namespace App\DTO;

use App\ITF\TicketITF;
use Symphograph\Bicycle\DTO\DTOTrait;

class TicketDTO implements TicketITF
{
    use DTOTrait;
    const string tableName = 'tickets';

    public int     $id;
    public int     $announceId;
    public ?int    $userId;
    public ?string $reservedAt;
    public int     $cellId;
    public bool    $offline;
    public int     $seatRow;
    public int     $seatNum;
    public string  $priceType;
    public bool    $hasAccount;

}