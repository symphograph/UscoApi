<?php
if(empty($_POST['year']))
    die();
$year = intval($_POST['year'] ?? date('Y'));
$filter = $_POST['filter'] ?? 0;
$filter = intval($filter);
if(!$filter)
    $filter = 1;

$filters = [
    1=>[0,1,1,0],
    3=>[0,0,1,0],
    2=>[0,0,0,1]
];
$filter = $filters[$filter] ?? [0,1,1,0];
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();

/*
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
*/
$Entryes = Entry::getCollection($year,$filter);
foreach ($Entryes as $en){
    $Entry = Entry::clone($en);
    echo $Entry->PrintItem();
}