<?php
if(empty($_POST['year']))
    die();
$year = $_POST['year'] ?? date('Y');
$year = intval($year);

$filter = $_POST['filter'] ?? 0;
$filter = intval($filter)+1;


$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';


$qwe = qwe("
SELECT * FROM `news`
WHERE YEAR(`date`) = '$year'
AND `show` = '$filter'
order by date DESC 
");
if(!$qwe or !$qwe->num_rows){
    die('Нет новостей');
}
NewsCol($qwe);