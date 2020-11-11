<?php
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) or $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest')
    die();
if ($_SERVER['REQUEST_METHOD'] != 'POST')
    die();

if(empty($_POST))
    die();
if(!isset($_SERVER['HTTP_X_CSRF_TOKEN']))
    die();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/../includs/check.php';

if(!TokenValid($identy))
    die('reload');
$email = $_POST['email'] ?? '';
if(empty($email))
    die('email');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    die('email');

$name = $_POST['name'] ?? '';
if(empty($name))
    die('name');
$name = filter_var($name, FILTER_SANITIZE_STRING);

$msg = $_POST['msg'] ?? '';
if(empty($msg))
    die('msg');
$msg = filter_var($msg, FILTER_SANITIZE_STRING);

$to      = 'roman.chubich@gmail.com';
$subject = 'Проверка работы почты';
$message = '
<div style="color: black; font-size: 16px">
    Получено сообщение из формы обратной связи на сайте sakh-orch.ru<br><br>
    Имя: '.$name.'.<br>
    Email: '. $email . '<br>
    Текст сообщения:<br>
    <div style="
    font-size: 14px;
    min-width: 20em;
    min-height: 10em;
    border: 2px solid rgba(215,181,137,1.00); 
    border-radius: 0.5em;
    background-color: #eaddcb;
    padding: 1em;
    ">
    ' . str_replace("\n",'<br>',$msg)  .'
    </div><br>
    <span style="color: red; font-size: 14px">
    Нет никаких гарантий того, что имя или email соответствуют действительности.
    <span><br>
</div>
';
$headers = array(
    'Content-type' => 'text/html',
    'charset'=> 'utf-8',
    'From' => 'feedback@sakh-orch.ru',
    'Reply-To' => $email,
    'X-Mailer' => 'PHP/' . phpversion()
);

mail($to, $subject, $message, $headers);
echo 'ok';

