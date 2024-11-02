<?php

namespace App\Img\Entry;

use Symphograph\Bicycle\Api\Action\ApiAction;
use App\Entry\Entry;
use App\Entry\Errors\EntryNoExists;
use Symphograph\Bicycle\Files\FileImgCTRL;
use App\Files\ImgList;
use Symphograph\Bicycle\Files\UploadedImg;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\AppStorage;
use Symphograph\Bicycle\Errors\Upload\EmptyFilesErr;
use Symphograph\Bicycle\HTTP\Request;

class EntryPhotoCTRL extends FileImgCTRL
{
    public static function add(): void
    {
        User::auth([13]);
        Request::checkEmpty(['entryId']);

        if (empty($_FILES)) {
            throw new EmptyFilesErr();
        }

        if(count($_FILES) > 20){
            AppStorage::$warnings[] = 'Слишком много файлов';
        }

        $Entry = Entry::byId($_POST['entryId'])
            ?: throw new EntryNoExists();

        foreach ($_FILES as $FILE) {
            $file = new UploadedImg($FILE);
            $FileIMG = parent::addIMG($file);
            Entry::linkPhoto($Entry->id, $FileIMG->id);
        }

        ImgList::runResizeWorker();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function unlink(): void
    {
        User::auth([13]);
        Request::checkEmpty(['entryId', 'imgId']);

        Entry::unlinkPhoto($_POST['entryId'], $_POST['imgId']);

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function unlinkAll(): void
    {
        User::auth([13]);
        Request::checkEmpty(['entryId']);

        Entry::unlinkAllPhotos($_POST['entryId']);

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    /*
    public static function del(): void
    {
        User::auth([1, 2, 4]);
        $entryId = intval($_POST['entryId'] ?? 0) or throw new ValidationErr();
        $baseName = $_POST['fileName'] ?? throw new ValidationErr();

        $Photo = new EntryPhoto($entryId, $baseName);
        $Photo->delFiles();
        Response::success();
    }
*/
    #[NoReturn] public static function getList(): void
    {
        Request::checkEmpty(['entryId']);

        $Entry = Entry::byId($_POST['entryId']);
        $Entry->initData();

        Response::data($Entry->Photos);
    }
}