<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\User;

session_start();


if(empty($_GET['token'])){
    die('emptyTokennnn');
}
$token = $_GET['token'];
$User = User::byCheck(0);

$qwe = qwe2("SELECT * FROM sessions where token = :token",['token'=>$token]);
if(!$qwe or !$qwe->rowCount()){
    die('badToken');
}
$q = $qwe->fetchObject();

$qwe = qwe2("SELECT * FROM personal WHERE id = :id",['id'=>$q->pers_id]);
if(!$qwe or !$qwe->rowCount()){
    die('unknown user');
}
$q = $qwe->fetchObject();
if(isset($User->id) && $q->id == $User->id){
    $User->setLvl($User->chkLvl($q->id,$token));
    qwe("
        UPDATE sessions 
        SET user_id = :user_id,
        token = :token
        WHERE id = :sess_id",
        [
            'user_id'=>$q->id,
            'token' => $token,
            'sess_id'=>$User->Sess->id
        ]
    );
    $User->goToSPA();
}


$User::create($q->id,$q->tele_id,$User->chkLvl($q->id,$token));

qwe("UPDATE sessions 
    SET user_id = :user_id,
        token = :token
    WHERE id = :sess_id",
    [
        'user_id'=>$q->id,
        'token' => $token,
        'sess_id'=>$User->Sess->id
    ]
);

$User->goToSPA();