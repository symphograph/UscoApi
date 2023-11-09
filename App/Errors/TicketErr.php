<?php

namespace App\Errors;


use Symphograph\Bicycle\Errors\MyErrors;

class TicketErr extends MyErrors
{
    protected string $type     = 'TicketErr';
    public function __construct(string $message = 'Ticket has an error', string $pubMsg = 'Билет с ошибкой', int $httpStatus = 500)
    {
        parent::__construct($message, $pubMsg, $httpStatus);

    }
}