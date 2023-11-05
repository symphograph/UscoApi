<?php

use App\Env\UscoEnv;
use JetBrains\PhpStorm\Language;
use Symphograph\Bicycle\Env\Env;
use Symphograph\Bicycle\Env\Server\ServerEnvCli;
use Symphograph\Bicycle\Env\Server\ServerEnvHttp;
use Symphograph\Bicycle\Env\Server\ServerEnvITF;
use Symphograph\Bicycle\PDO\DB;

/**
 * Generate a random string, using a cryptographically secure
 * pseudorandom number generator (random_int)
 *
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 *
 * @param int $length How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

function printr($var): void
{
    if (!Env::isDebugMode())
        return;
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function curl($plink, array $data = [])
{
    $data['apiKey'] = UscoEnv::getApiKey();
    //printr($data);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_FAILONERROR, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // allow redirects
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // times out after 4s
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // return into a variable
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_URL, $plink);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 GTB6");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $somepage = curl_exec($curl);
    //print_r($somepage);
    curl_close($curl);
    return $somepage;
}

function qwe(#[Language("SQL")] string $sql, array $args = [], string $connectName = 'default'): false|PDOStatement
{
    return DB::qwe($sql, $args, $connectName);
}

function qwe2(#[Language("SQL")] string $sql, array $args = []): false|PDOStatement
{
    return DB::qwe($sql, $args, 'staff');
}

function getRoot(): string
{
    return dirname(__DIR__);
}

function getServerEnvClass(): ServerEnvITF
{
    global $ServerEnv;
    if (isset($ServerEnv)) {
        return $ServerEnv;
    }
    if (PHP_SAPI === 'cli') {
        $ServerEnv = new ServerEnvCli();
    } else {
        $ServerEnv = new ServerEnvHttp();
    }
    return $ServerEnv;
}