<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use App\{Anonce, User};
use Symphograph\Bicycle\Api\Response;

$User = User::byCheck();

$data = Anonce::apiValidation();

$Anonces = Anonce::getCollectionByCache($data['sort'],$data['year'],$data['new'])
    or Response::error('No content', 204);

Response::data($Anonces);