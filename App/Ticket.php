<?php

namespace App;

use App\DTO\TicketDTO;

class Ticket extends DTO\TicketDTO
{
    public static function byId(int $id): self|false
    {
        $selfObject = new self();
        $selfObject->bindSelf(parent::byId($id));
        //$selfObject->initData();
        return $selfObject;
    }

}