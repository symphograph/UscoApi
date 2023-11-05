<?php

namespace App\CTRL;

use App\HallPlan;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\Errors\ValidationErr;

class HallPlanCTRL extends HallPlan
{
    public static function get(): void
    {
        //User::auth([1, 2, 4]);
        $id = ($_POST['id'] ?? false)
        or throw new ValidationErr();

        $HallPlan = HallPlan::byLast($id)
            or throw new NoContentErr();

        Response::data(compact('HallPlan'));
    }

    public static function put(): void
    {
        User::auth([1, 2, 4]);

        $plan = $_POST['plan'] ?? false or
        throw new ValidationErr('plan is empty');

        $plan = HallPlan::byBind($plan);
        $plan->putToDB();

        Response::success();
    }
}