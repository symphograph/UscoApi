<?php

namespace App\DTO;

use App\ITF\AnnounceITF;
use App\ITF\HallPlanITF;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\DB;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\NoContentErr;

class HallPlanDTO extends DTO implements HallPlanITF
{
    public int          $id;
    public array|string $pricing;
    public array|string $cells;
    public array|string $tickets;
    public array|string $structure;


    public static function byId(int $id): self
    {
        $Object = self::ifExist($id)
        or throw new NoContentErr("HallPlan $id", 'План не создан');
        return $Object;
    }

    public static function ifExist(int $id): self|false
    {
        $qwe = qwe("select * from hallPlans where id = :id", ['id' => $id]);
        return $qwe->fetchObject(self::class);
    }

    protected static function byChild(HallPlanITF $childObject): self
    {
        $objectDTO = new self();
        $objectDTO->bindSelf($childObject);
        return $objectDTO;
    }

    public function putToDB(): void
    {
        //$this->seats = json_encode($this->seats);
        $params = DB::initParams($this);
        DB::replace('hallPlans', $params);
    }
}