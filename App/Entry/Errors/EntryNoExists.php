<?php

namespace App\Entry\Errors;

class EntryNoExists extends EntryErr
{
    public function __construct(
        string $message = 'Entry does not exists',
        string $pubMsg = 'Новость не найдена',
        int $httpStatus = 400
    )
    {
        parent::__construct($message, $pubMsg, $httpStatus);
    }
}