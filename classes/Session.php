<?php

use JetBrains\PhpStorm\ArrayShape;

class Session
{

    public string      $id;
    public string|null  $user_id;
    private string      $identy;
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
        setcookie('sess_id',$_COOKIE['sess_id'], self::cookOpts(debug: $cfg->debug));
        return $sess;
    }

    public static function byId(string $id) : Session|bool
    {
        $qwe = qwe("SELECT * FROM sessions WHERE id = :id",['id'=>$id]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS,"Session")[0];
    }

    public static function byToken(string $token) : Session|bool
    {
        $qwe = qwe("SELECT * FROM sessions WHERE token = :token",['token'=>$token]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS,"Session")[0];
    }

    private static function delCook() : void
    {
        setcookie('sess_id','', self::cookOpts(expires: time()-3600));
    }

    private static function setNewIdenty($ip,$datetime,$cooktime): bool|string
    {
        global $cfg;
        $identy = random_bytes(12);
        $identy = bin2hex($identy);

        setcookie('identy',$identy,
                  Session::cookOpts(
                      expires : $cooktime,
                      samesite: 'Strict',
                      debug: $cfg->debug
                  )
        );
        $qwe = qwe("
        INSERT INTO `identy`
        (`identy`, `ip`, `time`, `last_ip`, `last_time`)
        VALUES
        (:identy, :ip, :datetime, :lip, :ldatetime)
        ",['identy'=>$identy,'ip'=>$ip,'datetime'=>$datetime,'lip'=>$ip,'ldatetime'=>$datetime]);

        if(!$qwe)
            return false;
        else
            return $identy;
    }

    private static function updateIdenty($ip,$datetime,$cooktime): bool|string
    {
        $identy = OnlyText($_COOKIE['identy']);
        if(iconv_strlen($identy) != 12 and iconv_strlen($identy) != 24) {
            setcookie ("identy", "", time() - 3600*24*360*10,"/", secure: true);
            return false;
        }

        $qwe = qwe("
        SELECT * FROM `identy`
        WHERE `identy` = :identy
        ",['identy'   => $identy]);
        if(!$qwe or !$qwe->rowCount()) {
            setcookie ("identy", "", time() - 3600*24*360*10,"/", secure: true);
            return false;
        }

        qwe("
            UPDATE `identy` SET
            `last_ip` = :ip,
            `last_time` = :datetime
            WHERE `identy` = :identy
            ",
            [
                 'ip'       => $ip,
                 'datetime' => $datetime,
                 'identy'   => $identy
            ]
        );
        global $cfg;
        setcookie('identy', $identy,
                  Session::cookOpts(
                      expires : $cooktime,
                      samesite: 'Strict',
                      debug: $cfg->debug
                  )
        );

        return $identy;
    }

    public static function chkIdenty(): bool|string
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $unix_time = time();
        $datetime = date('Y-m-d H:i:s',$unix_time);
        $cooktime = $unix_time+60*60*24*365*5;

        if(empty($_COOKIE['identy'])) {
            return self::setNewIdenty($ip,$datetime,$cooktime);
        }

        return self::updateIdenty($ip,$datetime,$cooktime);
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

    private static function newSess(string $identy): Session|bool
    {
        $id = random_bytes(12);
        $id = bin2hex($id);
        global $cfg;

        qwe("INSERT INTO sessions 
            (id, identy, token, first_ip, last_ip, datetime, last_time) 
            VALUES 
            (:id, :identy, :token, :first_ip, :last_ip, now(), now())",
            [
                'id'       => $id,
                'identy'   => $identy,
                'token'    => self::newToken(),
                'first_ip' => $_SERVER['REMOTE_ADDR'],
                'last_ip'  => $_SERVER['REMOTE_ADDR']
            ]
        );
        $sess = self::byId($id);
        if(!$sess){
            return false;
        }
        setcookie('sess_id',$id, self::cookOpts(debug: $cfg->debug));
        //setcookie('sess_id',$id, self::cookOpts());
        return $sess;
    }

    public static function check($identy): Session|bool
    {
        $sess = self::byCook();

        if(!$sess){
            $sess = self::newSess($identy);
        }
        return $sess;
    }

    public function getToken(): string
    {
        return $this->token;
    }

}