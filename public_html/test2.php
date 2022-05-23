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
$categs = Entry::categsByShow(2);
//$categs = array_filter($categs);

$Entryes = Entry::getCollection(2021,$categs);
foreach ($Entryes as $Entry){
    printr($Entry->id);
    //$Entry = Entry::clone($Entry);

    //$categs = Entry::categsByShow($Entry->show);
    //$Entry->categs = json_encode($categs,JSON_FORCE_OBJECT);
    //$Entry->catindex = implode('|',$categs);
    //$Entry->putToDB();


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