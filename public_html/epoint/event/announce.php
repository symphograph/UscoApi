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
    'listByMonth' => AnnounceCTRL::listByMonth(),
    'listByDate' => AnnounceCTRL::listByDate(),
    'futureList' => AnnounceCTRL::futureList(),
    'listAll' => AnnounceCTRL::listAll(),
    'get' => AnnounceCTRL::get(),
    'del' => AnnounceCTRL::del(),
    'delSketch' => AnnounceCTRL::delSketch(),
    'add' => AnnounceCTRL::add(),
    'update' => AnnounceCTRL::update(),
    'hide' => AnnounceCTRL::hide(),
    'show' => AnnounceCTRL::show(),
    'updateMarkdown' => AnnounceCTRL::updateMarkdown(),
    default => throw new ApiErr()
};