<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
if(isset($_POST) & count($_POST)) { $_SESSION['post'] = $_POST; }
if(isset($_SESSION['post']) && count($_SESSION['post'])) { $_POST = $_SESSION['post']; }
if(empty($_POST['token'])){
    die('emptyToken');
}
$token = $_POST['token'];
$User = User::byCheck(0);

$debug = (!empty($_POST['debug']) && $cfg->myip);

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
    header("Location: /");
}


$User::create($q->id,$q->tele_id,$User->chkLvl($q->id,$token));
qwe("UPDATE sessions 
    SET user_id = :user_id 
    WHERE id = :sess_id",
    [
        'user_id'=>$q->id,
        'sess_id'=>$User->Sess->id
    ]
);
//header("Location: /");
$User->goToSPA($debug);




//header("Location: https://localhost:8080/auth#{$User->Sess->getToken()}");