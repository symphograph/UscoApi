<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

/*$data = NewsItem::apiValidation()
or die(http_response_code(400));*/

$category = $_POST['category'] ?? false;
if(!$category)
    die(http_response_code(400));

$year = intval($_POST['year'] ?? 0);
if(!$year)
    $year = intval(date('Y',time()));

$limit = intval($_POST['limit'] ?? 1000);

$filters = [
    'usco'    => [0, 1, 1, 0],
    'euterpe' => [0, 0, 1, 0],
    'other'   => [0, 0, 0, 1],
    'all'     => [0, 1, 1, 1]
];


if(!array_key_exists($_POST['category'], $filters))
    die(http_response_code(400));
//$categs = Entry::categsByShow($filters);
//printr($categs);
$Item = Entry::getCollection($year, $filters[$category], $limit)
or die(APIusco::errorMsg("Нет новостей"));
echo APIusco::resultData($Item);