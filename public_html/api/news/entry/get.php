<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use App\Entry;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

$User = User::byCheck();

$id = intval($_POST['id'] ?? 0) or
    throw new ValidationErr('id');

$Item = Entry::byID($id) or
    throw new AppErr("Entry $id is empty", 'Новость не найдена');

Response::data($Item);