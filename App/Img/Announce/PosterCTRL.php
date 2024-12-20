<?php

namespace App\Img\Announce;

use App\Announce\Announce;
use App\Announce\Errors\NoExistsErr;
use Symphograph\Bicycle\Api\Action\ApiAction;
use App\Files\ImgList;
use Symphograph\Bicycle\Errors\Files\FileErr;
use Symphograph\Bicycle\Files\FileIMG;
use Symphograph\Bicycle\Files\FileImgCTRL;
use Symphograph\Bicycle\Files\UploadedImg;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\HTTP\Request;

class PosterCTRL extends FileImgCTRL
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

        $FileIMG = parent::addIMG(UploadedImg::getFile());
        //$FileIMG->makeSizes();
        Announce::linkPoster($Announce->id, $FileIMG->id);
        ApiAction::log(__FUNCTION__, self::class);
        ImgList::runResizeWorker();
        Response::success();
    }

    #[NoReturn] public static function unlink(): void
    {
        User::auth([14]);
        Request::checkEmpty(['announceId']);

        $announce = Announce::byId($_POST['announceId']);
        $poster = FileIMG::byId($announce->posterId);
        $poster->del();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    public static function get(): void
    {
        Request::checkEmpty(['announceId']);

        $announce = Announce::byId($_POST['announceId'])
            ?: throw new NoExistsErr($_POST['announceId']);

        $poster = FileIMG::byId($announce->posterId)
            ?: throw new FileErr();

        Response::data($poster);

    }

}