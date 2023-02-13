<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\StaffGroup;
use Symphograph\Bicycle\Helpers;
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$date = $_POST['date']
or die(http_response_code(400));
Helpers::isDate($date)
or die(http_response_code(400));

$StaffList = StaffGroup::getCollection($date)
or die(\App\api\Api::errorMsg('Хм.. Ничего не вижу.'));

echo json_encode(['data' => $StaffList]);