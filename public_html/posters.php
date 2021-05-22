<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';

$p_title = 'Афиши';
$ver = random_str(8);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','afisha.css','menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>

</head>

<body>


<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>

<div class="content">
    <div class="eventsarea">
        <div class="p_title">
            <div>
                <?php echo $p_title;?>
            </div>
            <div class="selectors">
                <select name="year" id="yearFilter">
                    <?php
                        $years = anonceYears();
                        foreach ($years as $k => $y){
                            $sel = '';
                            if($y == date('Y'))
                                $sel = ' selected ';
                            ?><option value="<?php echo $y?>"<?php echo $sel?>> <?php echo $y?> </option><?php
                        }
                    ?>
                </select>
                <select name="sort" id="sort">
                    <option value="0">Сначала новые</option>
                    <option value="1">Сначала старые</option>
                </select>
            </div>
        </div>
        <?php


        ?>
        <div class="gridarea" id="posters"></div>
    </div>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';

jsFile('posters.js');
?>


</body>
</html>