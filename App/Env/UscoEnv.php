<?php

namespace App\Env;

use Symphograph\Bicycle\Env\Env;

readonly class UscoEnv extends Env
{
    public string $staffApiDomain;
    public function __construct()
    {
        parent::__construct();
    }

    public static function getStaffApiDomain(): string
    {
        $env = require dirname($_SERVER['DOCUMENT_ROOT']) . '/includes/env.php';
        return $env->staffApiDomain;
    }

    public static function getApiKey(): string
    {
        $env = require dirname($_SERVER['DOCUMENT_ROOT']) . '/includes/env.php';
        return $env->apiKey;
    }

}