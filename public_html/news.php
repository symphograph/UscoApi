<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="yandex-verification" content="50b98ccfb33aa708" />
<?php
$p_title = 'Новости';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css', 'index.css', 'menum.css', 'right_nav.css']) ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';

?>


<div class="content">
    <div class="eventsarea">
        <?php
        $query = qwe("
        SELECT * from `news`
        WHERE `show` = 1
        ORDER BY `date` DESC
        ");

        ?>
        <div class="eventsarea">
            <div class="p_title">
                <div>
                    <?php echo $p_title;?>
                </div>
                <div class="selectors">
                    <?php selectYear(newsYears());?>

                    <select name="filter" id="filter">
                        <option value="0">Новости оркестра</option>
                        <option <?php if(isset($_GET['filter'])) echo ' selected ' ?> value="1">Прочие новости</option>
                    </select>
                </div>
            </div><br><br>
            <div class="newscol" id="news">

            </div>
        </div>
    </div>
</div>
<?php
    require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
    jsFile('news.js');
?>
</body>
</html>