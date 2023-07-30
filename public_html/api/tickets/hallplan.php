<?php

use App\Controllers\AnnounceCTRL;
use App\Controllers\HallPlanCTRL;
use App\User;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
if (empty($_POST['method'])) {
    throw new ValidationErr();
}


match ($_POST['method']) {
    'get' => HallPlanCTRL::get(),
    'put' => HallPlanCTRL::put(),
    default => throw new ApiErr()
};