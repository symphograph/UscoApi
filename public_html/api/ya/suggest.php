<?php

use App\Yandex\Geo\SuggestCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'list' => SuggestCTRL::list(),
    'get' => SuggestCTRL::get(),
    'add' => SuggestCTRL::add(),
    'update' => SuggestCTRL::update(),
    'del' => SuggestCTRL::del(),
    default => throw new ApiErr()
};