<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);

$id = intval($_POST['id'] ?? 0);
if (!$id)
    die(APIusco::errorMsg('Ошибка данных'));

Entry::delPw($id);

echo APIusco::resultMsg();