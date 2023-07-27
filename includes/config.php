<?php
use Symphograph\Bicycle\Env\Config;
use Symphograph\Bicycle\Errors\Handler;
use Symphograph\Bicycle\Logs\AccessLog;


session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], True, True);

Config::redirectFromWWW();
Config::initDisplayErrors();
Handler::regHandlers();
Config::checkPermission();
Config::postHandler();
\App\Env\Config::initEndPoints();
AccessLog::writeToLog();
