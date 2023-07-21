<?php

use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\Token\AccessToken;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
User::auth([1, 2]);

$plan = $_POST['plan'] ?? false or
throw new ValidationErr('plan is empty');

$hallId = $_POST['id'] ?? false
or throw new ValidationErr('hallId is empty');

$hallId = json_encode($hallId);
//qwe("update halls set plan = :plan where id = :id", ['plan' => $plan, 'id' => $id]);

Response::success();