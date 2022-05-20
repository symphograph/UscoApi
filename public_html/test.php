<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/functions.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
if(!$cfg->myip) exit;
//$User = User::byCheck();
//$User->apiAuth(100);
require_once dirname($_SERVER['DOCUMENT_ROOT'])."/vendor/autoload.php";

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

//$Parsedown = new Parsedown();
//echo $Parsedown->text($Entry->markdown);
/*
$Entry = Entry::byID(90);
Entry::parseMarkdown($Entry->markdown);
*/

//$NewsItem = new NewsItem(id: 90);
//$html = $NewsItem->PajeItem();
//echo $html;
/*
echo $Parsedown->parse($html);
echo $Parsedown->setUrlsLinked()
*/


use League\HTMLToMarkdown\HtmlConverter;
$converter = new HtmlConverter();
/*
$converter->getConfig()->setOption('hard_break', false);
//$converter = new HtmlConverter(array('strip_tags' => true));
//$html = "<h3>Quick, to the Batpoles!</h3>";
echo $converter->convert($html);
//echo $Parsedown->text('Hello _Parsedown_!');
*/

$entryes = Entry::getAlldbRows();
$Entryes = [];
foreach ($entryes as $en){
/*
    if($en->id != 53){
        continue;
        }
*/
    $Entry = Entry::clone($en);
    printr($Entry->id);
    $Entry->html = (new NewsItem($Entry->id))->content;
    $Entry->markdown = $Entry->html;
    //$Entry->markdown = str_replace('<br>',PHP_EOL,$Entry->markdown);
    $Entry->putToDB();

    /*
    $dom = new DOMDocument;
    @$dom->loadHTML($Entry->html);
    $images = $dom->getElementsByTagName('img');
    foreach ($images as $image) {
        $src = $image->getAttribute('src');
        if (str_starts_with($src, 'img/news') || str_starts_with($src, '/img/news')) {
            $fileName = pathinfo($src, PATHINFO_BASENAME);
            $fileName = explode('?', $fileName)[0];
            $src = '/' . pathinfo($src, PATHINFO_DIRNAME) . '/' . $fileName;
            //EntryImg::saveFromOld($src,$Entry->id);
            echo '<br>' . $Entry->id . ' - ' . $src . '<br>';
        }

    }
    */
    /*


        $Images = $Entry::getOldImages($Entry->id);
        foreach ($Images as $imgName){
            EntryImg::saveFromOld('/img/news/' . $Entry->id . '/' . $imgName);
            printr($imgName);
        }
    */
}


/*
$qwe = qwe("SELECT markdown FROM news WHERE id = 90");
$q = $qwe->fetchObject();
$arr = explode(PHP_EOL,$q->markdown);
printr($arr);
*/
//echo intval('001');
//echo str_pad(1,3,0,STR_PAD_LEFT);

//Entry::parseMarkdown($Entry->markdown);
//printr(Entry::getImages(90));
?>
</body>
</html>