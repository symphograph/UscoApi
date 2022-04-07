<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$data = Anonce::apiValidation();
if(!$data)
    die();

$qwe = Anonce::getCollection($data['sort'],$data['year']);
if(!$qwe)
    die();

foreach($qwe as $q)
{
    $Anonce = new AnonceCard();
    $Anonce->clone($q);
    $Anonce->printItem();
}
