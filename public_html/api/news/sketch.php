<?php

use App\Img\Entry\EntrySketchCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'del' => EntrySketchCTRL::del(),
    'add' => EntrySketchCTRL::add(),
    default => throw new ApiErr()
};
