<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(needPowers: [1,2,4]);

$id = intval($_POST['id'] ?? 0);
if (!$id)
    die(APIusco::errorMsg('Ошибка данных'));

Entry::delPw($id);

echo APIusco::resultMsg();