<?php

class User
{
    public string $ip = '';
    public string $identy = '';
    public Session|bool $Sess = false;

    public function check(): bool
    {
        $this->ip = $_SERVER['REMOTE_ADDR'];

        $identy = Session::chkIdenty();
        if(!$identy) return false;
        $this->identy = $identy;

        $Sess = Session::check($identy);
        if(!$Sess) return false;
        $this->Sess = $Sess;
        return true;
    }

    public static function byCheck(): User
    {
        $User = new User();
        $User->check();
        return $User;
    }
}