<?php
namespace App;

use App\DTO\AnnounceDTO;
use App\DTO\HallPlanDTO;
use App\ITF\HallPlanITF;
use Symphograph\Bicycle\DB;
use Symphograph\Bicycle\JsonDecoder;

class HallPlan extends HallPlanDTO implements HallPlanITF
{
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
        return $plan;
    }

    private function initData(): void
    {
        $this->cells = json_decode($this->cells);
        $this->pricing = json_decode($this->pricing);
        $this->tickets = json_decode($this->tickets);
        $this->structure = json_decode($this->structure, JSON_OBJECT_AS_ARRAY );
    }

    public static function findLast(int $id): self|false
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
        $planId = $qwe->fetchColumn();
        $HallPlan = self::byId($planId);
        $HallPlan->id = $id;
        return $HallPlan;
    }

    public function putToDB(): void
    {
        qwe("START TRANSACTION");
        parent::putToDB();
        qwe("delete from tickets where announceId = :announceId", ['announceId' => $this->id]);
        foreach ($this->tickets as $tic){
            $ticket = new Ticket();
            $ticket->bindSelf($tic);
            $ticket->announceId = $this->id;
            $ticket->putToDB();
        }
        qwe("COMMIT");
    }

}