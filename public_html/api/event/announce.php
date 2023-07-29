<?php

use App\Controllers\AnnounceCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'listByHall' => AnnounceCTRL::listByHall(),
    'listByYear' => AnnounceCTRL::listByYear(),
    'futureList' => AnnounceCTRL::futureList(),
    'get' => AnnounceCTRL::get(),
    'del' => AnnounceCTRL::del(),
    'add' => AnnounceCTRL::add(),
    'update' => AnnounceCTRL::update(),
    default => throw new ApiErr()
};
$Ann = new \App\Announce\Announce();