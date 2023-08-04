<?php

namespace App;

use App\Announce\Announce;
use App\DTO\TicketDTO;
use PDO;

class Ticket extends DTO\TicketDTO
{
    public static function byId(int $id): self|false
    {

        if(!$objectDTO = parent::byId($id)){
            return false;
        }
        $selfObject = new self();
        $selfObject->bindSelf($objectDTO);
        return $selfObject;
    }

    /**
     * @return self[]
     */
    public static function getListOfAnnounce(int $announceId): array
    {
        $qwe = qwe("select * from tickets where announceId = :announceId", ['announceId' => $announceId]);
        return $qwe->fetchAll(PDO::FETCH_CLASS, self::class) ?? [];
    }

    protected function isAvalible(): bool
    {
        return match (true){
            !empty($this->userId),
            !empty($this->reservedAt),
            $this->offline => false,
            default => true
        };
    }

    protected function isExpired(): bool
    {
        $Announce = Announce::byId($this->announceId);
        return $Announce->datetime > date();
    }
}