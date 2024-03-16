<?php

namespace App\Entry\Errors;

use Symphograph\Bicycle\Errors\MyErrors;

class EntryErr extends MyErrors
{
    public function __construct(
        string $message = 'Entry Error',
        string $pubMsg = 'C этой новостью что-то не так...',
        int $httpStatus = 500
    )
    {
        parent::__construct($message, $pubMsg, $httpStatus);
    }
}