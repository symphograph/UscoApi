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


$cfg = require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/ip.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/functions/functions.php';


if($cfg->myip) {
	ini_set('display_errors',1);
	error_reporting(E_ALL);
    //$cfg->debug = true;
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


    if($cfg->debug) {

    }
    cors();
}

if(str_starts_with($_SERVER['SCRIPT_NAME'],'/test/')){
    if(!$cfg->myip){
        die('permis');
    }
}
spl_autoload_register(function ($class_name) {
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/classes/' . $class_name . '.php';
});

$cfg->vueprod = '.prod';
if(str_starts_with($_SERVER['SERVER_NAME'],'test')){
    $cfg->vueprod = '';
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

