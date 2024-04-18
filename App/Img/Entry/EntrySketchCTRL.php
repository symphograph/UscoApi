<?php

namespace App\Img\Entry;

use App\Api\Action\ApiAction;
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
        User::auth([1, 2, 4]);
        Request::checkEmpty(['id']);

        $Entry = Entry::byId($_POST['id'])
            ?: throw new EntryNoExists();

        $FileIMG = parent::addIMG(UploadedImg::getFile());
        $FileIMG->makeSizes();
        Entry::linkSketch($Entry->id, $FileIMG->id);

        Response::success();
    }

    #[NoReturn] public static function del(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['entryId']);

        $Entry = Entry::byId($_POST['entryId']);
        $Sketch = new EntrySketch($Entry->id);
        $Sketch->delFiles();


        $Entry->initData();
        $Entry->initNewVerString();
        $Entry->putToDB();

        ApiAction::newInstance(__FUNCTION__, self::class)->putToDB();
        Response::success();
    }

    #[NoReturn] public static function unlink(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['id']);

        Entry::unlinkSketch($_POST['id']);

        ApiAction::newInstance(__FUNCTION__, self::class)->putToDB();
        Response::success();
    }
}