<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

use Symphograph\Bicycle\Debug\DebugCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

match ($_POST['method']) {
    'isDebugIp' => DebugCTRL::isDebugIp(),
    default => throw new ApiErr()
};