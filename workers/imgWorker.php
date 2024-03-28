<?php
set_time_limit(600);
use App\Files\ImgList;


require_once realpath(dirname(__DIR__) . '/vendor/autoload.php');

$ImgList = ImgList::unSized();
if(empty($ImgList->getList())){
    die();
}
$ImgList->makeSizes();


if(!empty(ImgList::unSized())) {
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