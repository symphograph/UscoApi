<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Entry, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\Helpers;

User::auth([1, 2, 4]);


$entry = $_POST['entry'] or
throw new ValidationErr('entry');

$id = intval($entry['id'] ?? 0) or
throw new ValidationErr('id');

$Entry = Entry::byID($id) or
throw new AppErr('Entry::byID err');

$Entry->title = $entry['title'] or
throw new ValidationErr('title', 'Пустой заголовок');

if(!Helpers::isDate($entry['date'] ?? '')){
    throw new ValidationErr('isDate err', 'Не вижу дату');
}
$Entry->date = $entry['date'];

$Entry->descr = $entry['descr'] or
throw new ValidationErr('descr err', 'Пустое описание');

$Entry->markdown = $entry['markdown'] or
throw new ValidationErr('markdown err', 'Пустой текст');

$Entry->categs = $entry['categs'] or
throw new ValidationErr('categs err', 'Ошибка определения категорий');

$Entry->refLink = $entry['refLink'] ?? '';
$Entry->refName = $entry['refName'] ?? '';

$Entry->putToDB() or
throw new AppErr('putToDB err', 'Ошибка при сохранении');

Response::success();