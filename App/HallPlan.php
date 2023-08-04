<?php
namespace App;

use App\DTO\AnnounceDTO;
use App\DTO\HallPlanDTO;
use App\ITF\HallPlanITF;
use Symphograph\Bicycle\DB;
use Symphograph\Bicycle\JsonDecoder;

class HallPlan extends HallPlanDTO implements HallPlanITF
{
    /**
     * @var Ticket[]
     */
    public array        $tickets;

    public static function byId(int $id): self
    {
        $selfObject = new self();
        $selfObject->bindSelf(parent::byId($id));
        $selfObject->initData();
        return $selfObject;
    }

    public static function byArray(array $plan): self
    {
        /** @var self $plan */
        $plan = JsonDecoder::cloneFromAny($plan, self::class);
        $tickets = [];
        foreach ($plan->tickets as $tic){
            $ticket = new Ticket();
            $ticket->bindSelf($tic);
            $tickets[] = $ticket;
        }
        $plan->tickets = $tickets;
        return $plan;
    }

    private function initData(): void
    {
        $this->cells = json_decode($this->cells);
        $this->pricing = json_decode($this->pricing);
        self::initTickets();
        $this->structure = json_decode($this->structure, JSON_OBJECT_AS_ARRAY );
    }

    private function initTickets(): void
    {
        $this->tickets = Ticket::getListOfAnnounce($this->id);
    }

    public static function byLast(int $id): self|false
    {
        $lastId = self::findLast($id);
        $HallPlan = self::byId($lastId);
        if($HallPlan->id === $id){
            return $HallPlan;
        }

        $HallPlan->id = $id;
        $tickets = [];
        foreach ($HallPlan->tickets as $ticket){
            unset($ticket->id);
            unset($ticket->userId);
            unset($ticket->reservedAt);
            unset($ticket->hasAccount);
            $tickets[] = $ticket;
        }
        $HallPlan->tickets = $tickets;
        $HallPlan->putToDB();
        return $HallPlan;
    }

    private static function findLast(int $id): int|false
    {
        $Announce = AnnounceDTO::byId($id);
        $hallId = $Announce->hallId;
        $qwe = qwe("
        select hp.id from announces an
        inner join hallPlans hp 
            on an.id = hp.id
            and an.hallId = :hallId
        order by an.datetime desc limit 1",
            ['hallId' => $hallId]
        );
        if(!$qwe || !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchColumn();
    }

    public function putToDB(): void
    {
        qwe("START TRANSACTION");
        $tickets = $this->tickets;
        unset($this->tickets);
        parent::putToDB();
        qwe("delete from tickets where announceId = :announceId", ['announceId' => $this->id]);
        foreach ($tickets as $ticket){
            $ticket->announceId = $this->id;
            $ticket->putToDB();
        }
        qwe("COMMIT");
    }

}