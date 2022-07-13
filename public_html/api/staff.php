<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

$date = $_POST['date']
or die(http_response_code(400));
Helpers::isDate($date)
or die(http_response_code(400));

$StaffList = StaffGroup::getCollection($date);
if(!$StaffList) {
    die(http_response_code(401));
}

echo json_encode(['data' => $StaffList]);