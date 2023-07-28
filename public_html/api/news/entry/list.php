<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Entry, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

$User = User::byCheck();


$category = ($_POST['category'] ?? false) or
    throw new ValidationErr('category', 'Категория не найдена');

if (!$year = intval($_POST['year'] ?? 0)) {
    $year = intval(date('Y', time()));
}

$limit = intval($_POST['limit'] ?? 1000);

$filters = [
    'usco'    => [0, 1, 1, 0],
    'euterpe' => [0, 0, 1, 0],
    'other'   => [0, 0, 0, 1],
    'all'     => [0, 1, 1, 1]
];


if(!array_key_exists($_POST['category'], $filters)){
    throw new ValidationErr('category', 'Категория не найдена');
}

$Item = Entry::getCollection($year, $filters[$category], $limit) or
 throw new AppErr('Entry::getCollection is empty', 'Нет новостей');

Response::data($Item);