<?php

use App\CTRL\TicketCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    'reserve' => TicketCTRL::reserve(),
    'returnTicket' => TicketCTRL::returnTicket(),
    'updateUserId' => TicketCTRL::updateUserId(),
    default => throw new ApiErr()
};