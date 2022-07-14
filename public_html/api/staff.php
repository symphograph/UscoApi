<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$date = $_POST['date']
or die(http_response_code(400));
Helpers::isDate($date)
or die(http_response_code(400));

$StaffList = StaffGroup::getCollection($date)
or die(\api\Api::errorMsg('Хм.. Ничего не вижу.'));

echo json_encode(['data' => $StaffList]);