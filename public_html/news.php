<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
$User = User::byCheck();
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
    <meta name="robots" content="noindex"/>
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
        /*$query = qwe("
        SELECT * from `news`
        WHERE `show` in (1,3)
        ORDER BY `date` DESC
        ");*/

        ?>
        <div class="eventsarea">
            <div class="p_title">
                <div>
                    <?php echo $p_title;?>
                </div>
                <div class="selectors">
                    <?php selectYear(newsYears());?>

                    <select name="filter" id="filter">
                        <?php
                        //$filter = $_GET['filter'] ?? 1;
                        $filter = intval($_GET['filter'] ?? 1);
                        $sel = ['',' selected '];
                        if(!$filter)
                            $filter = 1;

                        ?>
                        <option <?php echo $sel[intval($filter == 1)]?> value="1">Новости оркестра</option>
                        <option <?php echo $sel[intval($filter == 2)]?> value="2">Прочие новости</option>
                        <option <?php echo $sel[intval($filter == 3)]?> value="3">Звезды Эвтерпы</option>
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