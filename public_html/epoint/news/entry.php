<?php

use App\Entry\EntryCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'add' => EntryCTRL::add(),
    'del' => EntryCTRL::del(),
    'get' => EntryCTRL::get(),
    'list' => EntryCTRL::list(),
    'toplist' => EntryCTRL::toplist(),
    'update' => EntryCTRL::update(),
    'hide' => EntryCTRL::hide(),
    'show' => EntryCTRL::show(),
    'updateMarkdown' => EntryCTRL::updateMarkdown(),
    default => throw new ApiErr()
};