<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];
if(empty($_POST['groups']))
    die(http_response_code(400));
if(!is_array($_POST['groups']))
    die(http_response_code(400));
if(!count($_POST['groups']))
    die(http_response_code(400));

$groups = [];
foreach ($_POST['groups'] as $group) {
    $groups[] = StaffGroup::byArray($group);
}
if(!count($groups))
    die(http_response_code(400));

foreach ($groups as $group){
    $StaffGroup = StaffGroup::byQ($group);
    if($StaffGroup->group_id == 200){
        $StaffGroup->setUngroupedList();
        continue;
    }

    $StaffGroup->editPlacesOrder();
}
die('ok');
//echo json_encode(['data' => $StaffList]);