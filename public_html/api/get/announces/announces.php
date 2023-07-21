<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';
use App\{Announce, User};
use Symphograph\Bicycle\Api\Response;

$User = User::byCheck();

$data = Announce::apiValidation();

$Announces = Announce::getCollectionByCache($data['sort'],$data['year'],$data['new'])
    or Response::error('No content', 204);

Response::data($Announces);