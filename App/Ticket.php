<?php

namespace App;

use PDO;

class Ticket extends DTO\TicketDTO
{
    const int minsForTmpReserve = 15;

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

    protected function isReservExpired(): bool
    {
        if($this->hasAccount){
            return false;
        }
        return self::reservExpiredAt() < time();
    }

    private function reservExpiredAt(): int
    {
        if(empty($this->reservedAt)){
            return time() + 60 * self::minsForTmpReserve;
        }
        return strtotime($this->reservedAt) + 60 * self::minsForTmpReserve;
    }


    public static function unsetExpiredReserves(int $announceId): void
    {
        $tickets = self::getListOfAnnounce($announceId);
        foreach ($tickets as $ticket){
            if($ticket->isReservExpired()){
                $ticket->unsetUser();
                $ticket->putToDB();
            }
        }
    }

    protected function unsetUser(): void
    {
        $this->userId = null;
        $this->hasAccount = false;
        $this->reservedAt = null;
    }
}