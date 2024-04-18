<?php
namespace App;


use Symphograph\Bicycle\Env\Server\ServerEnv;
use Symphograph\Bicycle\Token\AccessToken;
use Symphograph\Bicycle\Token\AccessTokenData;
use Symphograph\Bicycle\Token\Token;

class User
{
    const authTypes = [
        'telegram'
    ];
    public int $id;
    public string $ip = '';
    public ?int $tele_id = null;
    public ?int $lvl = 0;
    public ?string $created;
    public ?string $last_time;
    public ?array $Powers;

    public static function auth(array $allowedPowers = []): void
    {
        AccessToken::validation(ServerEnv::HTTP_ACCESSTOKEN(), $allowedPowers);
    }

    public static function getIdByJWT(): int
    {
        $tokenArr = Token::toArray(ServerEnv::HTTP_ACCESSTOKEN());
        return $tokenArr['uid'];
    }

    public static function getPersId(): ?int
    {
        return AccessTokenData::persId();
    }
}