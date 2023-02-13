<?php

namespace App\Env;

readonly class Env
{
    private array  $debugIPs;
    private bool   $debugMode;
    private int    $adminAccountId;
    private string $frontendDomain;
    private object $telegram;
    private object $mailruSecrets;
    private object $discordSecrets;
    private string $apiKey;
    private string $staffApi;


    public function __construct()
    {
        self::initEnv();
    }

    private function initEnv(): void
    {
        $env = require dirname($_SERVER['DOCUMENT_ROOT']) . '/includes/env.php';
        $vars = (object)get_class_vars(self::class);
        foreach ($vars as $k => $v){
            if(!isset($env->$k)) continue;
            $this->$k = $env->$k;
        }
    }

    private static function getMyEnv(): self
    {
        global $Env;
        if(!isset($Env)){
            $Env = new self();
        }
        return $Env;
    }

    public static function isDebugIp(): bool
    {
        $Env = self::getMyEnv();
        return in_array($_SERVER['REMOTE_ADDR'], $Env->debugIPs);
    }

    public static function isDebugMode(): bool
    {
        $Env = self::getMyEnv();
        return $Env->debugMode && in_array($_SERVER['REMOTE_ADDR'], $Env->debugIPs);
    }

    public static function getAdminAccountId(): int
    {
        $Env = self::getMyEnv();
        return $Env->adminAccountId;
    }

    public static function getFrontendDomain(): string
    {
        $Env = self::getMyEnv();
        return $Env->frontendDomain;
    }

    public static function getApiKey(): string
    {
        $Env = self::getMyEnv();
        return $Env->apiKey;
    }

    public static function getStaffApi(): string
    {
        $Env = self::getMyEnv();
        return $Env->staffApi;
    }
}