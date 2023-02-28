<?php

use App\Env\UscoEnv;
use Symphograph\Bicycle\Env\Env;
use Symphograph\Bicycle\DB;

//Для числительных. (год, года, лет)
function number($n, $titles) {
$n = intval($n);	
  $cases = array(2, 0, 1, 1, 1, 2);
  return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}

/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

function printr($var) {
    if(!Env::isDebugMode())
        return;
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function ru_date($format, $date = false) {
	setlocale(LC_TIME, 'ru_RU.UTF-8');
	if (!$date) {
		$date = time();
	}
	if ($format === '') {
		$format = 'd %bg Y';
	}

	$months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
    $n = date('n', $date);
	$format = preg_replace("~\%bg~", $months[$n], $format);
    return date($format, $date);
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

function qwe(string $sql, array $args = null): bool|PDOStatement
{
    global $DB;
    if(!isset($DB)){
        $DB = new DB();
    }
    return $DB->qwe($sql,$args);
}

function qwe2(string $sql, array $args = null) : bool|PDOStatement
{
    global $DB2;
    if(!isset($DB2)){
        $DB2 = new DB('staff');
    }
    return $DB2->qwe($sql,$args);
}