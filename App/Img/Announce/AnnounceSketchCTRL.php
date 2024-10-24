<?php

namespace App\Img\Announce;

use App\Announce\Announce;
use App\Api\Action\ApiAction;
use Symphograph\Bicycle\Files\FileImgCTRL;
use Symphograph\Bicycle\Files\UploadedImg;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\HTTP\Request;

class AnnounceSketchCTRL extends FileImgCTRL
{
    public static function add(): void
    {
        User::auth([14]);
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

        ApiAction::newInstance(__FUNCTION__, self::class)->putToDB();
        Response::success();
    }
}