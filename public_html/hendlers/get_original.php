<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
if(!$cfg->myip) die();


$dir = 'img/avatars/upload/';
$pers_id = intval($_POST['pers_id']);
$rand = random_str(8);
//$basename = basename($_FILES['file']['name']);
$file_name = 'ava_'.$pers_id.'_'.$rand.'.png';
$path_max = $dir.$file_name;
$arr = [];
// var_dump($uplfile);

if(move_uploaded_file($_FILES['file']['tmp_name'],$root.'/'.$path_max))
{
    $arr['status'] = 'success';
    $arr['path_max'] = $path_max;
    $arr['file_max'] =  $file_name;

}else
{
    echo 'err';
}

header('Content-type: application/json');
echo json_encode($arr);
//var_dump($_POST);
?>