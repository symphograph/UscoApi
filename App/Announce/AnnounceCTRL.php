<?php

namespace App\Announce;


use App\User;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\NoContentErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\JsonDecoder;

class AnnounceCTRL
{
    public static function listByHall(): void
    {
        $hallId = intval($_POST['hallId'] ?? 0) or throw new ValidationErr();

        $AnnounceList = AnnounceList::byHall($hallId);
        if(empty($AnnounceList->getList())){
            throw new NoContentErr(httpStatus: 204);
        }
        $AnnounceList->initData();

        Response::data($AnnounceList->getList());
    }

    public static function listByYear(): void
    {
        $year = intval($_POST['year'] ?? 0) or throw new ValidationErr();

        $AnnounceList = AnnounceList::byYear($year);
        if(empty($AnnounceList->getList())){
            throw new NoContentErr();
        }
        $AnnounceList->initData();

        Response::data($AnnounceList->getList());
    }

    public static function futureList(): void
    {
        $AnnounceList = AnnounceList::byFuture();
        if(empty($AnnounceList->getList())){
            throw new NoContentErr();
        }
        $AnnounceList->initData();

        Response::data($AnnounceList->getList());
    }

    public static function allList(): void
    {
        $Announces = Announce::allCacheList()
            or throw new NoContentErr();

        $arr = [];
        $halls = [];
        foreach ($Announces as $announce){
            if(in_array($announce->hallId, $halls)){
                continue;
            }
            $halls[] = $announce->hallId;
            $arr[] = $announce;
        }


        Response::data($arr);
    }

    public static function get(): void
    {
        $id = intval($_POST['id'] ?? 0)
        or throw new ValidationErr('id');

        $Announce = Announce::byId($id)
        or throw new AppErr('Announce::byCache err', 'Анонс не найден');

        $Announce->initData();
        Response::data($Announce);
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);

        $announceId = intval($_POST['id'] ?? 0) or throw new ValidationErr();
        Announce::delete($announceId);
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


        if (empty($_POST['announce']))
            throw new ValidationErr('announce is empty');

       /** @var Announce $Announce */
       $Announce = JsonDecoder::cloneFromAny($_POST['announce'], Announce::class);

        $Announce->putToDB();

        Response::data($Announce);

    }

    public static function hide(): void
    {
        $announceId = intval($_POST['announceId'] ?? 0) or throw new ValidationErr();
        $Announce = AnnounceDTO::byId($announceId);
        $Announce->isShow = false;
        $Announce->putToDB();
        Response::success();
    }

    public static function show(): void
    {
        $announceId = intval($_POST['announceId'] ?? 0) or throw new ValidationErr();
        $Announce = AnnounceDTO::byId($announceId);
        $Announce->isShow = true;
        $Announce->putToDB();
        Response::success();
    }

}