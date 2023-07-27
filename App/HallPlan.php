<?php
namespace App;

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
/*
    public function putToDB(): void
    {
        //$this->seats = json_encode($this->seats);
        $params = DB::initParams($this);
        DB::replace('hallPlans', $params);
    }
*/
}