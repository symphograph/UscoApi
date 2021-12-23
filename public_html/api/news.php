<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/check.php';
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

/*$data = NewsItem::apiValidation()
or die(http_response_code(400));*/

$filters = [
    'usco'     => [1,3],
    'partners' => [2],
    'euterpe'  => [3],
    'all'      => [1,2,3]
];
if(!array_key_exists($_POST['category'], $filters))
    die(http_response_code(400));

$Item = NewsItem::getCollection(intval($_POST['year']), $filters[$_POST['category']])
or die(http_response_code(204));

echo $Item;