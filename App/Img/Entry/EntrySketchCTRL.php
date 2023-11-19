<?php

namespace App\Img\Entry;

use App\Entry\Entry;
use App\Img\FileImg;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

class EntrySketchCTRL
{
    public static function add(): void
    {
        User::auth([1, 2, 4]);

        if(empty($_FILES)){
            throw new ValidationErr('$_FILES is empty', 'Файлы не доставлены');
        }

        $entryId = intval($_POST['entryId'] ?? 0) or throw new ValidationErr();

        $file = array_shift($_FILES);
        $file = new FileImg($file);
        $Sketch = new EntrySketch($entryId);
        $Sketch->delFiles();
        $Sketch->upload($file);

        $Entry = Entry::byId($entryId);
        $Entry->initNewVerString();
        $Entry->putToDB();

        Response::success();
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);

        $announceId = intval($_POST['entryId'] ?? 0) or throw new ValidationErr();

        $Sketch = new EntrySketch($announceId);
        $Sketch->delFiles();

        $Entry = Entry::byId($announceId);
        $Entry->initNewVerString();
        $Entry->putToDB();

        Response::success();
    }
}