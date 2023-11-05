<?php

use App\CTRL\AnnounceCTRL;
use App\CTRL\HallPlanCTRL;
use App\User;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}


match ($_POST['method']) {
    'get' => HallPlanCTRL::get(),
    'put' => HallPlanCTRL::put(),
    default => throw new ApiErr()
};