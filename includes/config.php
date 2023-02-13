<?php
use App\Env\Config;

Config::redirectFromWWW();
Config::initDisplayErrors();
Config::checkPermission();
Config::initApiSettings();

session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], True, True);