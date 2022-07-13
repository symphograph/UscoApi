<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';

use api\{Api, PersPlace};


$User = User::byCheck();
$User->apiAuth(90);

$_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? null;
if(empty($_POST['groups']) || !is_array($_POST['groups']))
    die(http_response_code(400));

$date = $_POST['date']
or die(http_response_code(400));
Helpers::isDate($date)
or die(http_response_code(400));

$groups = [];
foreach ($_POST['groups'] as $group) {
    $groups[] = StaffGroup::byArray($group);
}
if(!count($groups))
    die(http_response_code(400));

foreach ($groups as $group){
    $StaffGroup = StaffGroup::checkClass($group);
    //printr($StaffGroup);
    if($StaffGroup->group_id == 200){
        $StaffGroup->setUngroupedList();
        continue;
    }

    $StaffGroup->editPlacesOrder($date);
}
PersPlace::runPlaceFixer();
echo Api::resultMsg();
//echo json_encode(['data' => $StaffList]);