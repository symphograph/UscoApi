<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';

$usersess = '';
$spect_id = '';
$admin = false;
$tester = false;

$User = User::byCheck();

$is_TSA = false;
?>