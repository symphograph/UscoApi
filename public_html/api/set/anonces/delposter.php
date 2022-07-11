<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
$User = User::byCheck();
$User->apiAuth(90);

$id = intval($_POST['id'] ?? 0);
if (!$id)
    die(APIusco::errorMsg('Не вижу id'));

if(empty($_POST['istop']))
    die(APIusco::errorMsg('Не вижу тип'));

if($_POST['istop'] === 'top'){
    Poster::delTopps($id);
}

if($_POST['istop'] === 'poster'){
    Poster::delPosters($id);
}
Anonce::reCache($id);

echo APIusco::resultMsg();