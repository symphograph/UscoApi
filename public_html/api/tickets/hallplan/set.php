<?php

use App\DTO\HallPlanDTO;
use App\HallPlan;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\Token\AccessToken;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
User::auth([1, 2]);

$plan = $_POST['plan'] ?? false or
throw new ValidationErr('plan is empty');

$id = $_POST['id'] ?? false
or throw new ValidationErr('hallId is empty');

$plan = HallPlan::byArray($plan);
$plan->putToDB();


Response::success();