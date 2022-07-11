<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
use api\Api;

$Halls = Hall::getList()
    or die(Api::errorMsg('Halls'));

echo Api::resultData(['Halls' => $Halls]);
