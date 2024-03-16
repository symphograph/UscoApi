<?php

use App\Announce\AnnounceCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

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
    'hide' => AnnounceCTRL::hide(),
    'show' => AnnounceCTRL::show(),
    'updateMarkdown' => AnnounceCTRL::updateMarkdown(),
    default => throw new ApiErr()
};