<?php
use Symphograph\Bicycle\Env\Config;
use Symphograph\Bicycle\Logs\AccessLog;


Config::redirectFromWWW();
Config::regHandlers();
Config::initDisplayErrors();
Config::checkPermission();
Config::initApiSettings();
AccessLog::writeToLog();
session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], True, True);