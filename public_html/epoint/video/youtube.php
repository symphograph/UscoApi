<?php

use App\Video\Youtube\YoutubeCTRL;
use Symphograph\Bicycle\Errors\ApiErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';


match ($_POST['method']) {
    'someLast' => YoutubeCTRL::someLast(),
    'allPublic' => YoutubeCTRL::allPublic(),
    default => throw new ApiErr()
};