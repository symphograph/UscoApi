<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$p_title = 'Документы';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
<?php CssMeta(['menu.css','index.css','menum.css', 'documents.css', 'right_nav.css'])?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
?>


<div class="content">

    <div class="text">
        <div class="p_title">Вместе против коррупции!</div>



        <div class="text">
            <img src="img/docs/logo-md.webp" width="30%"><br><br>
            Генеральной прокуратурой Российской Федерации в 2021 году организован Международный молодежный конкурс социальной антикоррупционной рекламы «Вместе против коррупции!» для молодежи всех государств мира.
            <br><br>
            Подробная информация о конкурсе, в том числе анонсирующие материалы и правила конкурса, размещена на сайте <a href="www.anticorruption.life">anticorruption.life</a>.
            <br><br>
            Прием работ будет осуществляться с 1 мая по 1 октября 2021 года на официальном сайте конкурса в двух номинациях - социальный плакат и социальный видеоролик.
        </div>
    </div>

</div>

<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>