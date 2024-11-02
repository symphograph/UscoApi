<?php

namespace App\Hall;

use Symphograph\Bicycle\Api\Action\ApiAction;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\HTTP\Request;
use Symphograph\Bicycle\PDO\PutMode;

class HallCTRL {

    #[NoReturn] public static function list(): void
    {
        $halls = HallList::all()
            ->initData()
            ->getList();
        Response::data($halls);
    }

    public static function get(): void
    {
        Request::checkEmpty(['id']);
        $hall = Hall::byId($_POST['id'])
            ?: throw new AppErr("Hall not found", "Зал не найден");
        $hall->initData();
        Response::data($hall);
    }

    #[NoReturn] public static function add(): void
    {
        User::auth([14]);
        Request::checkEmpty(['name', 'suggestId']);
        $hall = Hall::newInstance($_POST['name'], $_POST['suggestId']);
        $hall->putToDB(PutMode::insert);

        ApiAction::log(__FUNCTION__, self::class);
        $hall->initData();
        Response::data($hall);
    }

    public static function update(): void
    {
        User::auth([14]);
        Request::checkEmpty(['id', 'name', 'suggestId']);
        $hall = Hall::byId($_POST['id'])
            ?: throw new AppErr("Hall not found", "Зал не найден");
        $hall->bindSelf($_POST);
        $hall->putToDB();
        ApiAction::log(__FUNCTION__, self::class);
        $hall->initData();

        Response::data($hall);
    }

    #[NoReturn] public static function del(): void
    {
        User::auth([14]);
        Request::checkEmpty(['id']);
        Hall::delById($_POST['id']);
        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }
}