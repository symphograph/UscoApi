<?php

use App\HallPlan;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
User::auth([1, 2]);
$id = ($_POST['id'] ?? false)
or throw new ValidationErr();

try {
    $HallPlan = HallPlan::byId($id);
} catch (NoContentErr $err) {
    Response::success('Empty', 202);
}

Response::data(compact('HallPlan'));