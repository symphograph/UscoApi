<?php

use App\Album;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

$albumName = $_POST['albumName'] ?? false
or throw new ValidationErr('albumName is empty' );

$Album = Album::byName($albumName, true);
$Album->initImages();
Response::data(['Album' => $Album]);