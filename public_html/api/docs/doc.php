<?php

use App\Docs\DocCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'add' => DocCTRL::add(),
    'moveToFolder' => DocCTRL::moveToFolder(),
    'setAsTrash' => DocCTRL::setAsTrash(),
    'resFromTrash' => DocCTRL::resFromTrash(),
    'clearTrash' => DocCTRL::clearTrash(),
    'del' => DocCTRL::del(),
    'get' => DocCTRL::get(),
    'list' => DocCTRL::list(),
    'trashList' => DocCTRL::trashList(),
    default => throw new ApiErr()
};