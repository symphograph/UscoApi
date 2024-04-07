<?php

namespace App\Announce;


use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\HTTP\Request;
use Symphograph\Bicycle\JsonDecoder;

class AnnounceCTRL
{
    public static function listByHall(): void
    {
        Request::checkEmpty(['hallId']);

        $AnnounceList = AnnounceList::byHall($_POST['hallId']);
        if(empty($AnnounceList->getList())){
            throw new NoContentErr(httpStatus: 204);
        }
        $AnnounceList->initData();

        Response::data($AnnounceList->getList());
    }

    public static function listByYear(): void
    {
        Request::checkEmpty(['year']);

        $AnnounceList = AnnounceList::byYear($_POST['year']);
        if(empty($AnnounceList->getList())){
            throw new NoContentErr(httpStatus: 204);
        }
        $AnnounceList->initData();

        Response::data($AnnounceList->getList());
    }

    public static function futureList(): void
    {
        $date = $_POST['date'] ?? date('Y-m-d');
        $AnnounceList = AnnounceList::byFuture($date, true);
        if(empty($AnnounceList->getList())){
            throw new NoContentErr(httpStatus: 204);
        }
        $AnnounceList->initData();

        Response::data($AnnounceList->getList());
    }

    public static function get(): void
    {
        Request::checkEmpty(['id']);

        $Announce = Announce::byId($_POST['id'])
            ?: throw new NoContentErr();

        $Announce->initData();
        Response::data($Announce);
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['id']);

        Announce::delete($_POST['id']);
        Response::success();
    }

    public static function add(): void
    {
        User::auth([1, 2, 4]);

        $Announce = Announce::create() or
        throw new AppErr('addNewAnnounce err');

        Response::data($Announce);
    }

    public static function update(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['announce']);

       /** @var Announce $Announce */
       $Announce = JsonDecoder::cloneFromAny($_POST['announce'], Announce::class);

        $Announce->putToDB();
        $Announce->initData();
        Response::data($Announce);

    }

    public static function hide(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['announceId']);

        $Announce = AnnounceDTO::byId($_POST['announceId']);
        $Announce->isShow = false;
        $Announce->putToDB();
        Response::success();
    }

    public static function show(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['announceId']);

        $Announce = AnnounceDTO::byId($_POST['announceId']);
        $Announce->isShow = true;
        $Announce->putToDB();
        Response::success();
    }

    public static function updateMarkdown(): void
    {
        User::auth([1, 2, 4]);
        Request::checkEmpty(['id']);
        Request::checkSet(['markdown']);

        $Announce = Announce::byId($_POST['id']);
        $Announce->description = $_POST['markdown'];
        $Announce->initData();
        $Announce->putToDB();
        Response::data($Announce->parsedMD);
    }

}