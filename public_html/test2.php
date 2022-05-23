<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/includs/config.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']) . "/vendor/autoload.php";
if (!$cfg->myip) exit;

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
    <?php CssMeta(['menu.css', 'index.css', 'news.css', 'menum.css', 'right_nav.css']) ?>
</head>

<body>
<?php
//$file = '/img/entry/1080/1/pw/001.jpg';
//echo EntryImg::idByDir($file);

$Entryes = Entry::getAlldbRows();
foreach ($Entryes as $Entry){
    $Entry = Entry::clone($Entry);
    /*
    $EntryImg = EntryImg::copyPwFromOld($Entry);
    if($EntryImg->error){
        echo $Entry->id . ': ' . $EntryImg->error . '<br>';
    }else{
        echo $Entry->id . ': ' . $EntryImg->file . '<br>';
    }
    */

}

?>


</body>
</html>