<?php

namespace App\Announce\Errors;

use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Helpers;

class NoExistsErr extends AppErr
{
    public function __construct(int $announceId)
    {
        $this->type = Helpers::classBasename(self::class);
        parent::__construct("Announce $announceId does not exists", "Анонса не найден");
    }
}