<?php

namespace App\Docs;

use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\Errors\Upload\UploadErr;
use Symphograph\Bicycle\HTTP\Request;

class FolderCTRL
{
    public static function add(): void
    {
        User::auth([1, 2, 15]);
        Request::checkEmpty(['title']);

        $folder = DocFolder::create($_POST['title']);
        if(!$folder) {
            throw throw new UploadErr('err on create folder', 'Папка не создана');
        }

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
        Response::success();
    }

    public static function get()
    {
    }

    public static function list(): void
    {
        $folders = FolderList::allPublic();
        $folders->initData();
        Response::data(['list' => $folders->list]);
    }

    public static function setAsTrash(): void
    {
        User::auth([1, 2, 15]);
        Request::checkEmpty(['id']);

        $folder = DocFolder::byId($_POST['id'])
            ?: throw new AppErr("folder does not exist", "Папка не найдена");
        $folder->initData();
        $folder->setAsTrash();
        Response::success();
    }

    public static function resFromTrash(): void
    {
        User::auth([1, 2, 15]);
        Request::checkEmpty(['id']);

        $folder = DocFolder::byId($_POST['id'])
            ?: throw new AppErr("folder does not exist", "Папка не найдена");
        $folder->initData();
        $folder->resFromTrash();
        Response::success();
    }

    public static function posUp(): void
    {
        User::auth([1, 2, 15]);
        Request::checkEmpty(['id']);

        DocFolder::posUp($_POST['id']);
        Response::success();
    }

    public static function posDown(): void
    {
        User::auth([1, 2, 15]);
        Request::checkEmpty(['id']);

        DocFolder::posDown($_POST['id']);
        Response::success();
    }

    public static function rename(): void
    {
        User::auth([1, 2, 15]);
        Request::checkEmpty(['id', 'title']);

        $folder = DocFolder::byId($_POST['id']) ?: throw new NoContentErr();
        $folder->title = $_POST['title'];
        $folder->putToDB();
        Response::success();
    }
}