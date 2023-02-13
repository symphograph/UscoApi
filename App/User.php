<?php
namespace App;

use App\api\Api;
use App\Env\Env;
use JetBrains\PhpStorm\NoReturn;
use PDO;

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

    private function getToken() : bool|string
    {
        if(!$this->Sess)
            return false;
        $token = $this->Sess->getToken();
        return $token ?? false;
    }

    public function apiAuth(int $needLvl = 0, $needPowers = [])
    {
        if(!$this->Sess){
            die(http_response_code(401));
        }

        $_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? null;
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['token'] ?? null;
        if(empty($token)){
            die(Api::errorMsg('emptyToken'));
        }

        if(!$this->Sess->tokenValid($token)){
            die(Api::errorMsg('badToken'));
        }
        if($this->lvl < $needLvl){
            die(Api::errorMsg('Недостаточно прав'));
        }

        if(!empty($needPowers)){
            self::chkPower($needPowers) or die(Api::errorMsg('Недостаточно прав'));
        }


    }

    public function chkPower(array $needPowers) : bool
    {
        self::initPowers();
        return !empty(array_intersect($needPowers,$this->Powers));
    }

    public function chkLvl(int $pers_id, string $token)
    {
        $staffApi = Env::getStaffApi();
        $curl = curl(
            "https://$staffApi/api/pers.php",
            [
                'pers_id'=>$pers_id,
                'token' => $token
            ]
        );
        $pers = json_decode($curl);

        if(!empty($pers->lvl)){
            $this->lvl = $pers->lvl;
        }
        return $pers->lvl;
    }

    public function setLvl(int $lvl) : void
    {
        qwe("UPDATE users SET lvl = :lvl WHERE id = :id",['lvl'=>$lvl,'id'=>$this->id]);
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

    public static function authByToken(int $lvl = 0, $needPowers = [])
    {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['token'] ?? null;
        if(empty($token)){
            die(Api::errorMsg('emptyToken'));
        }

        $Sess = Session::byToken($token);
        if(!$Sess){
            die(Api::errorMsg('badToken'));
        }
        if(!$lvl){
            return true;
        }

        if(!$Sess->user_id){
            die(Api::errorMsg('badToken'));
        }

        $User = User::byId($Sess->user_id);
        if(!$User){
            die(Api::errorMsg('errorUser'));
        }

        if($User->lvl && $User->lvl >= $lvl){
            return true;
        }
        if(!empty($needPowers)){
            $User->chkPower($needPowers) or die(Api::errorMsg('Недостаточно прав'));
        }
        die(Api::errorMsg('badLvl'));
    }

    #[NoReturn] public function goToSPA(): void
    {
        $spaUrl = Env::getFrontendDomain();

        if(str_starts_with($_SERVER['HTTP_REFERER'],'https://dev.') || str_starts_with($_SERVER['HTTP_REFERER'],'http://dev.')){
            $spaUrl = 'dev.sakh-orch.ru';
        }

        $token = self::getToken();
        header("Location: https://$spaUrl/auth?#{$token}");
        die();
    }

}