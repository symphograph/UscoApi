<?php

namespace App\Yandex\Geo;

use App\User;
use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\HTTP\Request;

class SuggestCTRL {

    #[NoReturn] public static function add(): void
    {
        User::auth([1,2,14]);
        Request::checkEmpty(['suggest']);
        $suggestData = new YaGeoSuggestResult($_POST['suggest']);
        $suggest = $suggestData->getSuggest();
        $suggest->putToDB();
        Response::data($suggest);
    }

    #[NoReturn] public static function getApiKey(): void
    {
        User::auth([1,2,14]);
        Response::data(['key'=> 'ytre']);
    }

    public static function list()
    {
    }

    public static function get()
    {
    }

    public static function update()
    {
    }

    public static function del()
    {
    }
}