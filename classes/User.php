<?php

class User
{
    public int $id;
    public string $ip = '';
    public string $identy = '';
    public int|null $tele_id = null;
    public int $lvl = 0;
    public Session|bool $Sess = false;

    public static function byId(int $id) : User|bool
    {
        $qwe = qwe("SELECT * FROM users WHERE id = :id",['id'=>$id]);
        if(!$qwe or !$qwe->rowCount()){
            return false;
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS,"User")[0] ?? false;
    }

    private function checkSess(bool $noCreate = false): bool
    {
        if($noCreate && empty($_COOKIE['identy'])){
            return false;
        }
        $this->ip = $_SERVER['REMOTE_ADDR'];

        $identy = Session::chkIdenty();
        if(!$identy) return false;
        $this->identy = $identy;

        $Sess = Session::check($identy);
        if(!$Sess) return false;
        $this->Sess = $Sess;
        return true;
    }

    public static function byCheck(bool $noCreate = false): User|bool
    {
        $User = new User();
        $User->checkSess($noCreate);
        if(!$User->Sess){
            return $User;
        }
        if($User->Sess->user_id) {
            $Sess = $User->Sess;
            $User = User::byId($Sess->user_id);
            $User->Sess = $Sess;
        }

        return $User;
    }

    public function apiAuth(int $lvl = 0)
    {
        if(!$this->Sess){
            die(http_response_code(401));
        }

        $_POST = json_decode(file_get_contents('php://input'), true)['params'] ?? null;
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['token'] ?? null;
        if(empty($token)){
            die(json_encode(['status'=>'emptyToken','error'=>'Ошибка авторизации']));
        }

        if(!$this->Sess->tokenValid($token)){
            die(json_encode(['status'=>'badToken', 'error' => 'Ошибка авторизации']));
        }
        if($this->lvl < $lvl){
            die(json_encode(['status'=>'badLvl', 'error'=>'Недостаточно прав']));
        }
    }

    public function chkLvl(int $pers_id)
    {
        $curl = curl('https://test.mbugko.ru/api/pers.php',['pers_id'=>1]);
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

    public static function authByToken(int $lvl = 0)
    {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['token'] ?? null;
        if(empty($token)){
            die(json_encode(['status'=>'emptyToken']));
        }

        $Sess = Session::byToken($token);
        if(!$Sess){
            die(json_encode(['status'=>'badToken']));
        }
        if(!$lvl){
            return true;
        }

        if(!$Sess->user_id){
            die(json_encode(['status'=>'noUser']));
        }

        $User = User::byId($Sess->user_id);
        if(!$User){
            die(json_encode(['status'=>'errorUser']));
        }

        if($User->lvl && $User->lvl >= $lvl){
            return true;
        }

        die(json_encode(['status'=>'badLvl']));
    }
}