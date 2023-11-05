<?php

use App\CTRL\EntryCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'add' => EntryCTRL::add(),
    'get' => EntryCTRL::get(),
    'list' => EntryCTRL::list(),
    'update' => EntryCTRL::update(),
    default => throw new ApiErr()
};