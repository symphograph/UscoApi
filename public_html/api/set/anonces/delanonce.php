<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Anonce, Poster, User};

$User = User::byCheck();
$User->apiAuth(needPowers: [1,2,4]);

$id = $_POST['id'] ?? 0;
if(!$id)
    die(json_encode(['error'=>'Ошибка данных']));

$qwe = Anonce::delete($_POST['id']);
if (!$qwe){
    die(json_encode(['error'=>'Ошибка данных']));
}
Poster::delPosters($id);
Poster::delTopps($id);
echo json_encode(['result'=>'Ok']);