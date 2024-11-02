<?php

namespace App\Docs;

use Symphograph\Bicycle\Api\Action\ApiAction;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\Errors\Upload\UploadErr;
use Symphograph\Bicycle\HTTP\Request;

class FolderCTRL
{
    public static function add(): void
    {
        User::auth([15]);
        Request::checkEmpty(['title']);

        $folder = DocFolder::create($_POST['title']);
        if(!$folder) {
            throw throw new UploadErr('err on create folder', 'Папка не создана');
        }

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    public static function del(): void
    {
        User::auth([1, 2]);
        Request::checkEmpty(['id']);

        $folder = DocFolder::byId($_POST['id'])
            ?: throw new AppErr("folder does not exist", "Папка не найдена");

        $folder->initData();
        $folder->del();
        ApiAction::newInstance(__FUNCTION__, self::class)->putToDB();
        Response::success();
    }

    public static function get()
    {
    }

    #[NoReturn] public static function list(): void
    {
        $folders = FolderList::allPublic();
        $folders->initData();
        Response::data(['list' => $folders->list]);
    }

    public static function setAsTrash(): void
    {
        User::auth([15]);
        Request::checkEmpty(['id']);

        $folder = DocFolder::byId($_POST['id'])
            ?: throw new AppErr("folder does not exist", "Папка не найдена");
        $folder->initData();
        $folder->setAsTrash();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    public static function resFromTrash(): void
    {
        User::auth([15]);
        Request::checkEmpty(['id']);

        $folder = DocFolder::byId($_POST['id'])
            ?: throw new AppErr("folder does not exist", "Папка не найдена");
        $folder->initData();
        $folder->resFromTrash();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function posUp(): void
    {
        User::auth([15]);
        Request::checkEmpty(['id']);

        DocFolder::posUp($_POST['id']);

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function posDown(): void
    {
        User::auth([15]);
        Request::checkEmpty(['id']);

        DocFolder::posDown($_POST['id']);

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    public static function rename(): void
    {
        User::auth([15]);
        Request::checkEmpty(['id', 'title']);

        $folder = DocFolder::byId($_POST['id']) ?: throw new NoContentErr();
        $folder->title = $_POST['title'];
        $folder->putToDB();

        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }
}