<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$new_id = $_GET['new_id'] ?? 0;
$new_id = intval($new_id);
if(!$new_id)
{
    header("Location: ../news.php");
    die();
}

$qwe = qwe("
SELECT * from `news`
WHERE `new_id` = '$new_id'
AND `show`
");
if(!$qwe or !$qwe->num_rows)
{
    header("Location: ../news.php");
    die();
}
$q = mysqli_fetch_object($qwe);

$img = '<img src="'.$q->img.'" width="320px"/>';
$ntitle = $q->new_tit;


$p_title = 'Южно-Сахалинский камерный оркестр';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','news.css','menum.css', 'right_nav.css'])?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>


<div class="content">
    <div class="newsarea">
        <div class="ntitle"><?php echo $ntitle;?></div><hr>
        <div class="narea">

        <?php
        $file = $root.'/news/new_'.$new_id.'.php';
        if(file_exists($file))
            include_once $file;
        elseif(!$q->content)
        {
            echo '<div class="text">' . $q->descr . '</div>';
        }else
        {
            echo '<div class="text">' . $q->content . '</div>';
        }



        ?></div>
    </div>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>