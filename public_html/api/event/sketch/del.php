<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use App\{Announce\Announce, Poster, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

User::auth([1, 2, 4]);

$id = intval($_POST['id'] ?? 0) or
throw new ValidationErr('id');

Poster::delTopps($id);
Announce::reCache($id);

Response::success();