<?php

namespace App\Controllers;

use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\Errors\ValidationErr;

class HallPlanCTRL extends \App\HallPlan
{
    public static function get(): void
    {
        //User::auth([1, 2, 4]);
        $id = ($_POST['id'] ?? false)
        or throw new ValidationErr();

        $HallPlan = self::findLast($id)
            or throw new NoContentErr();
        $tickets = [];
        foreach ($HallPlan->tickets as $k => $v){
            if(isset($v->id)){
                $v->cellId = $v->id;
                unset($v->id);
            }
            $tickets[$k] = $v;
        }
        $HallPlan->tickets = $tickets;
        Response::data(compact('HallPlan'));
    }

    public static function put(): void
    {
        User::auth([1, 2, 4]);

        $plan = $_POST['plan'] ?? false or
        throw new ValidationErr('plan is empty');

        $plan = self::byArray($plan);
        $plan->putToDB();

        Response::success();
    }
}