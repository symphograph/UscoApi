<?php

namespace App\Api\Action;



use App\User;
use Symphograph\Bicycle\DTO\ModelTrait;

class ApiAction extends ApiActionDTO
{
    use ModelTrait;

    public static function newInstance(
        string $method,
        string $controller,
        ?int $persId = null,
        array $postData = []
    ): static {
        $persId = User::getPersId();
        return parent::newInstance($method, $controller, $persId, $_POST);
    }
}