<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/includs/ip.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/functions/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/includs/check.php';
if(!$myip) die();
if(empty($_POST['photo']))
    die('empty');
$pers_id = intval($_POST['pers_id']);
//var_dump($_POST);
$arr = [];
$dir = 'img/avatars/small/';
$file_max = $_POST['photo_c'];
$file_max = pathinfo('img/avatars/upload/'.$file_max, PATHINFO_FILENAME);
if(!$file_max) die('bad_file_name');

//$rand_marker = explode('_',$file_name);
//$rand_marker = array_pop($rand_marker);

$file_min = 'ava_'.$pers_id.'_min.png';
$path_min = $dir . $file_min;


$img = str_replace(['data:image/png;base64',' '],['','+'],$_POST['photo']);

$fileData = base64_decode($img);

if(file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$path_min, $fileData))
    echo $file_min;

?>