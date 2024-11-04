<?php

namespace App\Announce;


use Symphograph\Bicycle\Api\Action\ApiAction;
use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\HTTP\Request;
use Symphograph\Bicycle\JsonDecoder;

class AnnounceCTRL
{
    public static function listAll(): void
    {
        $AnnounceList = AnnounceList::all();
        if(empty($AnnounceList->getList())){
            throw new NoContentErr(httpStatus: 204);
        }
        $AnnounceList->initData();

        Response::data($AnnounceList->getList());
    }

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

    public static function listByMonth(): void
    {
        Request::checkEmpty(['year', 'month']);

        $AnnounceList = AnnounceList::byMonth($_POST['year'], $_POST['month']);
        if(empty($AnnounceList->getList())){
            throw new NoContentErr(httpStatus: 204);
        }
        $list = $AnnounceList->initData()->getList();

        Response::data($list);
    }

    public static function listByDate(): void
    {
        Request::checkEmpty(['date']);

        $AnnounceList = AnnounceList::byDate($_POST['date']);
        if(empty($AnnounceList->getList())){
            throw new NoContentErr(httpStatus: 204);
        }
        $list = $AnnounceList->initData()->getList();

        Response::data($list);
    }

    #[NoReturn] public static function listFuture(): void
    {
        $date = $_POST['date'] ?? date('Y-m-d');
        $AnnounceList = AnnounceList::byFuture($date, true);
        /*
        if(empty($AnnounceList->getList())){
            throw new NoContentErr(httpStatus: 204);
        }
        */
        $AnnounceList->initData();

        Response::data($AnnounceList->getList());
    }

    public static function get(): void
    {
        Request::checkEmpty(['announceId']);

        $Announce = Announce::byId($_POST['announceId'])
            ?: throw new NoContentErr();

        $Announce->initData();
        Response::data($Announce);
    }

    #[NoReturn] public static function del(): void
    {
        User::auth([14]);
        Request::checkEmpty(['id']);

        ApiAction::log(__FUNCTION__, self::class);
        Announce::delete($_POST['id']);
        Response::success();
    }

    public static function add(): void
    {
        User::auth([14]);

        $Announce = Announce::create() or
        throw new AppErr('addNewAnnounce err');

        ApiAction::log(__FUNCTION__, self::class);
        Response::data($Announce);
    }

    #[NoReturn] public static function update(): void
    {
        User::auth([14]);
        Request::checkEmpty(['announce']);

       /** @var Announce $Announce */
       $Announce = JsonDecoder::cloneFromAny($_POST['announce'], Announce::class);

        $Announce->putToDB();
        $Announce->initData();
        ApiAction::log(__FUNCTION__, self::class);
        Response::data($Announce);
    }

    #[NoReturn] public static function hide(): void
    {
        User::auth([14]);
        Request::checkEmpty(['announceId']);

        $Announce = AnnounceDTO::byId($_POST['announceId']);
        $Announce->isShow = false;
        $Announce->putToDB();
        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function show(): void
    {
        User::auth([14]);
        Request::checkEmpty(['announceId']);

        $Announce = AnnounceDTO::byId($_POST['announceId']);
        $Announce->isShow = true;
        $Announce->putToDB();
        ApiAction::log(__FUNCTION__, self::class);
        Response::success();
    }

    #[NoReturn] public static function updateMarkdown(): void
    {
        User::auth([14]);
        Request::checkEmpty(['announceId']);
        Request::checkSet(['markdown']);

        $Announce = Announce::byId($_POST['announceId']);
        $Announce->description = $_POST['markdown'];
        $Announce->initData();
        $Announce->putToDB();
        ApiAction::log(__FUNCTION__, self::class);
        Response::data($Announce->parsedMD);
    }

}