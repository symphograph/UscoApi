<?php
require_once dirname(__DIR__, 4) . '/vendor/autoload.php';

use App\{Img\EntryImg, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

User::auth([1, 2, 4]);

if(empty($_FILES)){
    throw new ValidationErr('$_FILES is empty', 'Файлы не доставлены');
}

foreach ($_FILES as $FILE){
    $EntryImg = EntryImg::upload($FILE,1);
    if($EntryImg->error){
        throw new AppErr($EntryImg->error,$EntryImg->error);
    }
}

Response::success();