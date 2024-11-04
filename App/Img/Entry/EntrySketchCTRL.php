<?php

namespace App\Img\Entry;

use Symphograph\Bicycle\Api\Action\ApiAction;
use App\Entry\Entry;
use App\Entry\Errors\EntryNoExists;
use Symphograph\Bicycle\Files\FileImgCTRL;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Files\UploadedImg;
use Symphograph\Bicycle\HTTP\Request;

class EntrySketchCTRL extends FileImgCTRL
{
    public static function add(): void
    {
        User::auth([13]);
        Request::checkEmpty(['id']);

        $Entry = Entry::byId($_POST['id'])
            ?: throw new EntryNoExists();

        $FileIMG = parent::addIMG(UploadedImg::getFile());
        $FileIMG->makeSizes();
        Entry::linkSketch($Entry->id, $FileIMG->id);

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function del(): void
    {
        User::auth([13]);
        Request::checkEmpty(['carrierId']);

        $Entry = Entry::byId($_POST['carrierId']);
        $Sketch = new EntrySketch($Entry->id);
        $Sketch->delFiles();


        $Entry->initData();
        $Entry->initNewVerString();
        $Entry->putToDB();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function unlink(): void
    {
        User::auth([13]);
        Request::checkEmpty(['carrierId']);

        Entry::unlinkSketch($_POST['carrierId']);

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }
}