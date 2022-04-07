<?php
if(empty($_POST['year']))
    die();
$year = $_POST['year'] ?? date('Y');
$year = intval($year);

$filter = $_POST['filter'] ?? 0;
$filter = intval($filter);
if(!$filter)
    $filter = 1;

$filters = [
    1=>'1,3',
    2=>2,
    3=>3
];
$filter = $filters[$filter] ?? '1,3';
$filter = ' ('.$filter.')';
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();


$qwe = qwe("
SELECT * FROM `news`
WHERE YEAR(`date`) = '$year'
AND `show` in $filter
order by date DESC 
");
if(!$qwe or !$qwe->rowCount()){
    die('Нет новостей');
}
NewsCol($qwe);