<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

/*$data = NewsItem::apiValidation()
or die(http_response_code(400));*/
$category = $_POST['category'] ?? false;
if(!$category)
    die(http_response_code(400));

$year = $_POST['year'] ?? 0;
$year = intval($year);
if(!$year) $year = date('Y',time());

$limit = $_POST['limit'] ?? 100;
$limit = intval($limit);

$filters = [
    'usco'    => [1, 3],
    'other'   => [2],
    'euterpe' => [3],
    'all'     => [1, 2, 3]
];
if(!array_key_exists($_POST['category'], $filters))
    die(http_response_code(400));

$Item = NewsItem::getCollection($year, $filters[$category],$limit)
or die(http_response_code(204));

echo $Item;