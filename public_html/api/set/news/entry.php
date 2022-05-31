<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);


$entry = $_POST['entry'] or
die(APIusco::errorMsg('Ошибка запроса'));

$id = intval($entry['id'] ?? 0) or
die(APIusco::errorMsg('Ошибка запроса'));

$Entry = Entry::byID($id);
$Entry->title = $entry['title'] or
die(APIusco::errorMsg('Пустой заголовок'));

$Entry->date = Validator::date($entry['date'] ?? '') or
die(APIusco::errorMsg('Не вижу дату'));

$Entry->descr = $entry['descr'] or
die(APIusco::errorMsg('Пустое описание'));

$Entry->markdown = $entry['markdown'] or
die(APIusco::errorMsg('Пустой текст'));

$Entry->categs = $entry['categs'] or
die(APIusco::errorMsg('Ошибка определения категорий'));

$Entry->putToDB() or
die(APIusco::errorMsg('Ошибка при сохранении'));


echo APIusco::resultMsg();
