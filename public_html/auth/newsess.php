<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
if(empty($_COOKIE['identy'])){
    die('identy');
}
$User = User::byCheck();

header("Location: /");