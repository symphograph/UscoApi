<?php
namespace App;

use PDO;
use Symphograph\Bicycle\Errors\AuthErr;
use Symphograph\Bicycle\Token\AccessToken;
use Symphograph\Bicycle\Token\Token;

class User
{
    public int $id;
    public string $ip = '';
    public ?int $tele_id = null;
    public ?int $lvl = 0;
    public ?string $created;
    public ?string $last_time;
    public Session|bool $Sess = false;
    public ?array $Powers;

    public function __set(string $name, $value): void
    {
    }

    public static function byId(int $id) : User|bool
    {
        $qwe = qwe("SELECT * FROM users WHERE id = :id",['id'=>$id]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchObject(self::class);
    }

    private function checkSess(bool $noCreate = false): bool
    {
        if($noCreate && (empty($_COOKIE['sess_id']))){
            return false;
        }

        $this->ip = $_SERVER['REMOTE_ADDR'];

        $Sess = Session::check();

        if(!$Sess) return false;
        $this->Sess = $Sess;
        return true;
    }

    public static function byCheck(bool $noCreate = false): User|bool
    {
        if(str_starts_with($_SERVER['SCRIPT_NAME'], '/api/')){
            $noCreate = true;
        }
        $User = new User();
        $User->checkSess($noCreate);
        if(!$User->Sess){
            return $User;
        }

        if($User->Sess->user_id) {
            $Sess = $User->Sess;
            $User = User::byId($Sess->user_id);
            $User->Sess = $Sess;
            $User->initPowers();
        }

        return $User;
    }

    public static function getPowers(int $user_id) : array
    {
        $qwe = qwe2("
            select power_id from pers_power 
            where pers_id = :user_id",
            ['user_id' => $user_id]
        );
        if(!$qwe || !$qwe->rowCount()){
            return  [];
        }
        return $qwe->fetchAll(PDO::FETCH_COLUMN);
    }

    public function initPowers(): void
    {
        $this->Powers = self::getPowers($this->id);
    }

    public function apiAuth(int $needLvl = 0, $needPowers = [])
    {
        if(!$this->Sess){
            die(http_response_code(401));
        }

        $_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? null;
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['token'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if(empty($token)){
            throw new AuthErr('emptyToken');
        }

        if(!$this->Sess->tokenValid($token)){
            throw new AuthErr('badToken');
        }
        if($this->lvl < $needLvl){
            throw new AuthErr('badLvl', 'Недостаточно прав', 403);
        }

        if(!empty($needPowers)){
            self::chkPower($needPowers)
                or throw new AuthErr('badPower', 'Недостаточно прав', 403);
        }

    }

    public static function auth(array $allowedPowers = []): void
    {
        AccessToken::validation($_SERVER['HTTP_ACCESSTOKEN'], $allowedPowers);
    }

    public static function getIdByJWT(): int
    {
        $tokenArr = Token::toArray($_SERVER['HTTP_ACCESSTOKEN']);
        return $tokenArr['uid'];
    }

    public function chkPower(array $needPowers) : bool
    {
        self::initPowers();
        return !empty(array_intersect($needPowers,$this->Powers));
    }

    public static function create($id,$tele_id = 0,$lvl = 0)
    {
        qwe("INSERT INTO users 
            (id, tele_id, created, last_time,lvl) 
            VALUES 
            (:id, :tele_id, now(), now(),:lvl)",
            ['id'=>$id, 'tele_id'=>$tele_id,'lvl'=>$lvl]
        );
    }

}