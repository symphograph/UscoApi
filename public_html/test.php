<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/functions.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
if(!$cfg->myip) exit;
//$User = User::byCheck();
//$User->apiAuth(100);

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
    <?php CssMeta(['menu.css','index.css','news.css','menum.css', 'right_nav.css'])?>
</head>

<body>
<?php
$qwe = qwe("SELECT concert_id, datetime FROM anonces");
foreach ($qwe as $q){
    $date = date('Y-m-d',strtotime($q['datetime']));
    $folder = '/img/posters/topp/origins/';
    $ext = '.png';
    $file = $folder . $date . $ext;
    $file = $_SERVER['DOCUMENT_ROOT'] . $file;
    if(!file_exists($file)) continue;
    $to = $_SERVER['DOCUMENT_ROOT'] . $folder . 'poster_' . $q['concert_id'] . $ext;
    echo $to . '<br>';
    rename($file,$to);
    //echo "<img src='$file'>";
}
//$files = FileHelper::FileList('/img/posters/origins/');
//printr($files);

?>
</body>
</html>