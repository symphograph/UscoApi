<?php

namespace App\Controllers;


use App\Poster;
use App\User;
use Exception;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\JsonDecoder;

class AnnounceCTRL extends \App\Announce\Announce
{
    public static function listByHall(): void
    {
        if (empty(intval($_POST['hallId'] ?? false))) {
            throw new ValidationErr();
        }

        $Announces = self::hallCachList($_POST['hallId'])
        or Response::error('No content', 204);

        Response::data($Announces);
    }

    public static function listByYear(): void
    {
        if (empty(intval($_POST['year'] ?? false))) {
            throw new ValidationErr();
        }

        $Announces = self::yearCachList($_POST['year'])
        or Response::error('No content', 204);

        Response::data($Announces);
    }

    public static function futureList(): void
    {
        $Announces = self::futureCachList()
        or Response::error('No content', 204);

        Response::data($Announces);
    }

    public static function get(): void
    {
        $id = intval($_POST['id'] ?? 0)
        or throw new ValidationErr('id');

        self::reCache($id);
        $Announce = self::byCache($id)
        or throw new AppErr('Announce::byCache err', 'Анонс не найден');

        Response::data($Announce);
    }

    public static function del(): void
    {
        User::auth([1, 2, 4]);

        $id = intval($_POST['id'] ?? 0)
        or throw new ValidationErr('id');

        try {
            self::delete($_POST['id']);
            Poster::delPosters($id);
            Poster::delTopps($id);
        } catch (Exception $err) {
            throw new AppErr($err->getMessage(), 'Ошибка при удалении');
        }

        Response::success();
    }

    public static function add(): void
    {
        User::auth([1, 2, 4]);

        $Announce = self::addNewAnnounce() or
        throw new AppErr('addNewAnnounce err');

        Response::data($Announce);
    }

    public static function update(): void
    {
        User::auth([1, 2, 4]);


        if (empty($_POST['announce']))
            throw new ValidationErr('announce is empty');

        /** @var parent $Announce */
        $Announce = JsonDecoder::cloneFromAny($_POST['announce'], self::class);
        $Announce->putToDB();

        Response::data($Announce);

    }

}