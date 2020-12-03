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
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';

if(!TokenValid($identy))
    die('reload');

if(!empty($_POST['email2']))
    die();

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

$arr = [
    'sakh-orch.ru'=>'mbu-gko@yandex.ru',
    'test.sakh-orch.ru'=>'roman.chubich@gmail.com'
    ];

$to      = $arr[$_SERVER['SERVER_NAME']];
$subject = 'Сообщение от: '.$name;
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

$email = mysqli_real_escape_string($dbLink,$email);
$name = mysqli_real_escape_string($dbLink,$name);
$msg = mysqli_real_escape_string($dbLink,$msg);
$msg_id = GetNextFreeFeedMailId();
$msg_key = random_str(12);
$agent = $_SERVER['HTTP_USER_AGENT'];
$agent = mysqli_real_escape_string($dbLink,$agent);

$qwe = qwe("INSERT INTO `feed_mails` 
(`msg_id`, `email`, `name`, `msg`, `identy`,`msg_time`, `ip`,`agent`,`msg_key`) 
VALUES 
('$msg_id','$email','$name','$msg', '$identy',now(),'$ip','$agent','$msg_key')
");
if(!$qwe)
    die();


if(mail($to, $subject, $message, $headers))
    echo 'ok';

