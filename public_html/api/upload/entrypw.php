<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{APIusco, EntryImg, User};

User::authByToken(needPowers: [1,2,4]);

if(empty($_FILES)){
    die(http_response_code(400));
}

foreach ($_FILES as $FILE){
    $EntryImg = EntryImg::upload($FILE,1);
    if($EntryImg->error){
        die(APIusco::errorMsg($EntryImg->error));
    }
}
echo APIusco::resultMsg();