<?php

namespace App\Img;

use App\Announce\Announce;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

class SketchCTRL
{
    public static function add(): void
    {
        User::auth([1, 2, 4]);

        if(empty($_FILES)){
            throw new ValidationErr('$_FILES is empty', 'Файлы не доставлены');
        }

        $announceId = intval($_POST['announceId'] ?? 0) or throw new ValidationErr();

        $file = array_shift($_FILES);
        $file = new FileImg($file);
        $Sketch = new PosterSketch($announceId);
        $Sketch->upload($file);

        $Announce = Announce::byId($announceId);
        $Announce->initNewVerString();
        $Announce->putToDB();

        Response::success();
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);

        $announceId = intval($_POST['announceId'] ?? 0) or throw new ValidationErr();

        $Sketch = new PosterSketch($announceId);
        $Sketch->delFiles();

        $Announce = Announce::byId($announceId);
        $Announce->initNewVerString();
        $Announce->putToDB();

        Response::success();
    }

    public static function get(): void
    {
        $announceId = intval($_POST['announceId'] ?? 0) or throw new ValidationErr();
        $Sketch = PosterSketch::getIMG($announceId);
        Response::data($Sketch);
    }
}