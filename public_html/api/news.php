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
    'usco' => 1,
    'other'=> 2,
    'euterpe' => 3,
    'all' => 4
];

$categs = Entry::categsByShow($filters[$category]);
if(!array_key_exists($_POST['category'], $filters))
    die(http_response_code(400));
//printr($categs);
$Item = Entry::getCollection($year, $categs, $limit)
//$Item = NewsItem::getCollection($year, $filters[$category],$limit)
or die(http_response_code(204));
echo APIusco::resultData($Item);