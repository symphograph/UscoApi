<?php

namespace App\Errors;

use Symphograph\Bicycle\Logs\ErrorLog;

class TicketErr extends \Symphograph\Bicycle\Errors\MyErrors
{
    protected string $type     = 'TicketErr';
    public function __construct(string $message = 'Ticket has an error', string $pubMsg = 'Билет с ошибкой', int $httpStatus = 500)
    {
        parent::__construct($message, $pubMsg, $httpStatus);

    }
}