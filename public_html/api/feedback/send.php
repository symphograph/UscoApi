<?php

use App\Feedback\FormHandlerLax;
use App\Feedback\FormHandlerStrict;
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\ValidationErr;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';


match ('strict'){
    'strict' => FormHandlerStrict::processForm(),
    'lax' => FormHandlerLax::processForm(),
    default => throw new ValidationErr()
};

/**
 *  JSON-ответ с заданным сообщением и HTTP-статусом.
 *
 * @param string $msg        Сообщение для включения в ответ (default success).
 * @param int    $statusCode HTTP-статус код ответа (default 200 OK).
 *
 * @return void
 */
Response::success();
