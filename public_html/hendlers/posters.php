<?php

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
$data = Anonce::apiValidation() or die();

$qwe = Anonce::getCollection($data['sort'],$data['year']) or die();

foreach($qwe as $q)
{
    if (!$q->show)
        continue;
    $Anonce = Anonce::clone($q);
    $Anonce->printCard();
}
