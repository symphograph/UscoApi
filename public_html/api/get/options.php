<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\api\Api;
use App\Hall;

$Halls = Hall::getList()
    or die(Api::errorMsg('Halls'));

echo Api::resultData(['Halls' => $Halls]);
