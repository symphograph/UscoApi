<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{APIusco, Entry, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

User::auth([1, 2, 4]);

$id = intval($_POST['id'] ?? 0) or
    throw new ValidationErr('id');

Entry::delPw($id);
Response::success();