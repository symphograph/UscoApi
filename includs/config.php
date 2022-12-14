<?php

if(preg_match('/www./',$_SERVER['SERVER_NAME']))
{
	$server_name = str_replace('www.','',$_SERVER['SERVER_NAME']);
	$ref=$_SERVER["QUERY_STRING"];
	if ($ref!="") $ref="?".$ref;
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: https://".$server_name."/".$ref);
	exit();
}
use Symphograph\Bicycle\DB;
$env = require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/ip.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/functions/functions.php';

session_set_cookie_params(0, "/", $_SERVER['SERVER_NAME'], True, True);
if($env->myip) {
	ini_set('display_errors',1);
	error_reporting(E_ALL);
    //$env->debug = true;
}

if(
    str_starts_with($_SERVER['SCRIPT_NAME'], '/hendlers/')
    ||
    str_starts_with($_SERVER['SCRIPT_NAME'], '/api/')
)
{
    if(!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'OPTIONS']))
        die();

    if(empty($_POST)){
        $_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? [];
    }


    if($env->debug) {

    }
    //cors();
}

if(str_starts_with($_SERVER['SCRIPT_NAME'],'/test/')){
    if(!$env->myip && !$env->server_ip){
        die('permis');
    }
}

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php';

spl_autoload_register(function ($className) {
    $fileName = str_replace('\\', '/', $className) . '.php';
    $file = dirname($_SERVER['DOCUMENT_ROOT']) . '/classes/' . $fileName;
    if(file_exists($file)){
        require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/classes/' . $fileName;
    }

});

$env->vueprod = '.prod';
if(str_starts_with($_SERVER['SERVER_NAME'],'test')){
    $env->vueprod = '';
}

//------------------------------------------------------------------

function qwe(string $sql, array $args = null): bool|PDOStatement
{
    global $DB;

    if(!isset($DB)){
        $DB = new DB();
    }

    return $DB->qwe($sql,$args);
}

function qwe2(string $sql, array $args = null) : bool|PDOStatement
{
    global $DB2;

    if(!isset($DB2)){
        $DB2 = new DB(1);
    }

    return $DB2->qwe($sql,$args);
}