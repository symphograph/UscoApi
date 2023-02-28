<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Anonce, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

$User = User::byCheck();

$id = intval($_POST['id'] ?? 0)
    or throw new ValidationErr('id');

Anonce::reCache($id);
$Anonce = Anonce::byCache($id)
    or throw new AppErr('Anonce::byCache err', 'Анонс не найден');

Response::data($Anonce);