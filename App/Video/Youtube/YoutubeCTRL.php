<?php

namespace App\Video\Youtube;

use JetBrains\PhpStorm\NoReturn;
use Symphograph\Bicycle\Api\Response;

class YoutubeCTRL {

    #[NoReturn] public static function someLast(): void
    {
        $list = YoutubeList::someLast($_POST['limit'] ?? 1000);
        Response::data($list->getList());
    }

    #[NoReturn] public static function allPublic(): void
    {
        $list = YoutubeList::allPublic($_POST['limit'] ?? 1000);
        Response::data($list->getList());
    }
}