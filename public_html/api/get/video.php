<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\Video;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;

$limit = intval($_POST['limit'] ?? 1000);

$VideoList = Video::getCollection($limit) or
throw new AppErr('VideoList err', 'Видео не найдено');

Response::data($VideoList);