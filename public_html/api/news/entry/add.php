<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use App\{Entry, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;

User::auth([1, 2, 4]);

$Entry = Entry::addNewEntry() or
throw new AppErr('addNewEntry err');

Response::data($Entry);
