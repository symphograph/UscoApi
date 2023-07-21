<?php

use App\Album;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

$Albums = Album::getList();
rsort($Albums);
if(empty($Albums)) throw new AppErr('Album::getOptions() err', 'Список не найден');
Response::data(['Albums' => $Albums]);