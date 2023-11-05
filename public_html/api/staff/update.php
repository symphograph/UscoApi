<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use App\{StaffGroup, User};
use App\api\{PersPlace};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\Helpers;

User::auth([1, 2, 4]);

if(!Helpers::isDate($_POST['date'] ?? '')){
    throw new ValidationErr('date', 'Ошибка даты');
}

if(empty($_POST['groups']) || !is_array($_POST['groups'])){
    throw new ValidationErr('groups');
}

$groups = [];
foreach ($_POST['groups'] as $group) {
    $groups[] = StaffGroup::byArray($group);
}
if(!count($groups)){
    throw new AppErr('StaffGroup::byArray err','Группы не найдены');
}

foreach ($groups as $group){
    $StaffGroup = StaffGroup::checkClass($group);
    if($StaffGroup->groupId == 200){
        $StaffGroup->setUngroupedList();
        continue;
    }
    $StaffGroup->editPlacesOrder($_POST['date']);
}
PersPlace::runPlaceFixer();

Response::success();