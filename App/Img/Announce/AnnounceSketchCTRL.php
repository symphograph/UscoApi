<?php

namespace App\Img\Announce;

use App\Announce\Announce;
use App\Entry\Entry;
use App\Files\FileImgCTRL;
use App\Files\UploadedImg;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\HTTP\Request;

class AnnounceSketchCTRL extends FileImgCTRL
{
    public static function add(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['id']);

        $Announce = Announce::byId($_POST['id'])
        or throw new ValidationErr(
            "Announce {$_POST['id']} does not exists",
            "Анонса не существует"
        );

        $uploadedImg = UploadedImg::getFile();
        $FileIMG = parent::addIMG($uploadedImg);
        $FileIMG->makeSizes();
        Announce::linkSketch($Announce->id, $FileIMG->id);

        Response::success();
    }

    #[NoReturn] public static function unlink(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['id']);

        Announce::unlinkSketch($_POST['id']);

        Response::success();
    }

}