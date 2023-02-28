<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Anonce, Poster, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

$User = User::byCheck();
$User->apiAuth(needPowers: [1,2,4]);

$id = intval($_POST['id'] ?? 0)
    or throw new ValidationErr('id');

try {
    Anonce::delete($_POST['id']);
    Poster::delPosters($id);
    Poster::delTopps($id);
} catch (Exception $err) {
    throw new AppErr($err->getMessage(), 'Ошибка при удалении');
}

Response::success();