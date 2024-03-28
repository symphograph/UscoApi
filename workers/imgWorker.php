<?php
set_time_limit(600);
use App\Files\ImgList;
use Symphograph\Bicycle\Logs\Log;


require_once realpath(dirname(__DIR__) . '/vendor/autoload.php');
Log::msg('worker started', [], 'worker');
$ImgList = ImgList::unSized();
if(empty($ImgList->getList())){
    Log::msg('list is empty. exit.', [], 'worker');
    die();
}

Log::msg('start makeSizes part', [], 'worker');
$ImgList->makeSizes();
Log::msg('end makeSizes part', [], 'worker');

if(!empty(ImgList::unSized())) {
    Log::msg('start next part', [], 'worker');
    ImgList::runResizeWorker();
}

//printr($rrr);
/*
if ($argc > 1) {
    // $argv[1], $argv[2], ... содержат пути к файлам
    foreach ($argv as $key => $filePath) {
        if ($key === 0) continue; // Пропускаем имя скрипта

        // Здесь логика обработки каждого файла
        // Например, изменение размера, сжатие и т.д.
    }
}
*/