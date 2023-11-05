<?php

namespace App\Env;

use Symphograph\Bicycle\Env\Env;
use Symphograph\Bicycle\Env\Server\ServerEnv;

readonly class UscoEnv extends Env
{
    public string $staffApiDomain;
    public function __construct()
    {
        parent::__construct();
    }

    public static function getStaffApiDomain(): string
    {
        $env = require dirname(ServerEnv::DOCUMENT_ROOT()) . '/includes/env.php';
        return $env->staffApiDomain;
    }

    public static function getApiKey(): string
    {
        $env = require dirname(ServerEnv::DOCUMENT_ROOT()) . '/includes/env.php';
        return $env->apiKey;
    }

}