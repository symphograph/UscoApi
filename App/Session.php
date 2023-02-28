<?php
namespace App;

use App\api\Api;
use Symphograph\Bicycle\Env\Env;
use JetBrains\PhpStorm\ArrayShape;
use PDO;

class Session
{

    public string      $id;
    public string|null  $user_id;
    private string      $token;
    private string      $first_ip;
    private string      $last_ip;
    private string      $datetime;
    private string      $last_time;
    private string|null $agent;

    public function __set(string $name, $value): void
    {

    }

    public static function cookOpts(
        int         $expires = 0,
        string      $path = '/',
        string|null $domain = null,
        bool        $secure = true,
        bool        $httponly = true,
        string      $samesite = 'Strict', // None || Lax  || Strict
        bool        $debug = false
    ) : array
    {
        if(!$expires){
            $expires = time() + 60*60*24*30;
        }
        //$domain = $domain ?? $_SERVER['SERVER_NAME'];

        if($debug){
            return [
                'expires'  => $expires,
                'path'     => '/',
                'domain'   => null,
                'secure'   => true,
                'httponly' => true,
                'samesite' => 'None'
            ];
        }
        return [
            'expires'  => $expires,
            'path'     => $path,
            'domain'   => $domain,
            'secure'   => $secure,
            'httponly' => $httponly,
            'samesite' => $samesite // None || Lax  || Strict
        ];
    }

    private static function byCook() : Session|bool
    {
        if(empty($_COOKIE['sess_id'])){
            return false;
        }

        if(strlen($_COOKIE['sess_id']) != 24){
            return false;
        }

        $sess = self::byId($_COOKIE['sess_id']);
        if(!$sess){
            self::delCook();
            return false;
        }
        $sess->updateDB();
        setcookie('sess_id',$_COOKIE['sess_id'], self::cookOpts(debug: Env::isDebugMode()));
        return $sess;
    }

    public static function byId(string $id) : Session|bool
    {
        $qwe = qwe("SELECT * FROM sessions WHERE id = :id",['id'=>$id]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchObject(self::class);
    }

    public static function byToken(string $token) : Session|bool
    {
        $qwe = qwe("SELECT * FROM sessions WHERE token = :token",['token'=>$token]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchObject(get_class());
    }

    private static function delCook() : void
    {
        setcookie('sess_id','', self::cookOpts(expires: time()-3600));
    }


    private function updateDB() : void
    {
        $this->last_time = date('Y-m-d H:i:s');

        qwe("UPDATE sessions SET 
                    last_ip = :last_ip, 
                    last_time = :last_time WHERE 
                                                 id = :id",
            [
                'last_ip'   => $_SERVER['REMOTE_ADDR'],
                'id'        => $this->id,
                'last_time' => $this->last_time
            ]
        );
    }

    public function tokenValid(string $token) : bool
    {
        return $token === $this->token;
    }

    private static function newToken(): string
    {
        $token = random_bytes(12);
        return bin2hex($token);
    }

    private static function newSess(): Session|bool
    {
        $id = random_bytes(12);
        $id = bin2hex($id);

        qwe("INSERT INTO sessions 
            (id, token, first_ip, last_ip, datetime, last_time) 
            VALUES 
            (:id, :token, :first_ip, :last_ip, now(), now())",
            [
                'id'       => $id,
                'token'    => self::newToken(),
                'first_ip' => $_SERVER['REMOTE_ADDR'],
                'last_ip'  => $_SERVER['REMOTE_ADDR']
            ]
        );
        $sess = self::byId($id);
        if(!$sess){
            return false;
        }
        setcookie('sess_id',$id, self::cookOpts(debug: Env::isDebugMode()));
        header("Refresh:0",0);
        die();
    }

    public static function check(): Session|bool
    {
        $sess = self::byCook();

        if(!$sess){

            $sess = self::newSess();
        }
        return $sess;
    }

    public function getToken(): string
    {
        return $this->token;
    }

}