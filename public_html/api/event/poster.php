<?php

use App\Img\Announce\PosterCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'del' => PosterCTRL::del(),
    'add' => PosterCTRL::add(),
    default => throw new ApiErr()
};