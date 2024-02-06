<?php

namespace App\Img\Announce;

use App\Announce\Announce;
use App\Img\FileImg;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

class AnnounceSketchCTRL
{
    public static function add(): void
    {
        User::auth([1, 2, 4]);

        if(empty($_FILES)){
            throw new ValidationErr('$_FILES is empty', 'Файлы не доставлены');
        }

        $announceId = intval($_POST['id'] ?? 0) or throw new ValidationErr();
        $Announce = Announce::byId($announceId)
        or throw new ValidationErr(
            "Announce $announceId does not exists",
            "Анонса не существует"
        );

        $file = array_shift($_FILES);
        $file = new FileImg($file);
        $Sketch = new AnnounceSketch($announceId);
        $Sketch->delFiles();
        $Sketch->upload($file);


        $Announce->initNewVerString();
        $Announce->putToDB();

        Response::success();
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);

        $announceId = intval($_POST['announceId'] ?? 0) or throw new ValidationErr();

        $Sketch = new AnnounceSketch($announceId);
        $Sketch->delFiles();

        $Announce = Announce::byId($announceId);
        $Announce->initNewVerString();
        $Announce->putToDB();

        Response::success();
    }

}