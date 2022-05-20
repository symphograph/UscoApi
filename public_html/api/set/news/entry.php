<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);

$entry = $_POST['entry'] ??
    die(json_encode(['error'=>'Ошибка запроса']));

$id = intval($entry['id'] ?? 0) or
    die(json_encode(['error'=>'Ошибка запроса']));

$Entry = Entry::byID($id);
$Entry->title = $entry['title'] ??
    die(json_encode(['error'=>'Пустой заголовок']));

$Entry->date = Validator::date($entry['date'] ?? '') or
    die(json_encode(['error'=>'Не вижу дату']));

$Entry->markdown = $entry['markdown'] ??
    die(json_encode(['error'=>'Пустой текст']));

$Entry->putToDB() or
    die(json_encode(['error'=>'Ошибка при сохранении']));

echo json_encode(['result'=>'Готово']);
