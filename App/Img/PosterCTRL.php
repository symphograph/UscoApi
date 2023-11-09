<?php

namespace App\Img;

use App\Announce\Announce;
use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

class PosterCTRL
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
        $Poster = new PosterMain($announceId);
        $Poster->upload($file);

        $Announce = Announce::byId($announceId);
        $Announce->initNewVerString();
        $Announce->putToDB();

        Response::success();
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);

        $announceId = intval($_POST['announceId'] ?? 0) or throw new ValidationErr();

        $Poster = new PosterMain($announceId);
        $Poster->delFiles();

        $Announce = Announce::byId($announceId);
        $Announce->initNewVerString();
        $Announce->putToDB();

        Response::success();
    }

    public static function get()
    {
    }
}