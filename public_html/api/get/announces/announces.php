<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use App\{Anonce, APIusco, User};

$User = User::byCheck();

$data = Anonce::apiValidation()
or die(http_response_code(400));

$Anonces = Anonce::getCollectionByCache($data['sort'],$data['year'],$data['new'])
or die(http_response_code(204));

echo APIusco::resultData($Anonces);