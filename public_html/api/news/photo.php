<?php
set_time_limit(600);
use App\Img\Entry\EntryPhotoCTRL;
use Symphograph\Bicycle\Errors\ApiErr;
use Symphograph\Bicycle\Errors\ValidationErr;


require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

if (empty($_POST['method'])) {
    throw new ValidationErr();
}

match ($_POST['method']) {
    //'del' => EntryPhotoCTRL::del(),
    'unlink' => EntryPhotoCTRL::unlink(),
    'unlinkAll' => EntryPhotoCTRL::unlinkAll(),
    'add' => EntryPhotoCTRL::add(),
    'list' => EntryPhotoCTRL::getList(),
    default => throw new ApiErr()
};