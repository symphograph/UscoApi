<?php

use App\Hall\HallCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'list' => HallCTRL::list(),
    'get' => HallCTRL::get(),
    'add' => HallCTRL::add(),
    'update' => HallCTRL::update(),
    'del' => HallCTRL::del(),
    default => throw new ApiErr()
};