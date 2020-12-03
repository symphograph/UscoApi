<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once ROOT.'/includs/check.php';

$p_title = 'Вакансии';
$ver = random_str(8);

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="Тигран Ахназарян, Южно-Сахалинский камерный оркестр, оркестр">
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css', 'index.css', 'afisha.css', 'menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<?php
//FacebookScript();
require_once ROOT.'/includs/links.php';
require_once ROOT.'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>

<div class="content">

<div class="eventsarea">
    <div class="text">
        <br><br>
        <em>Группа струнных инструментов:</em>
        <ul>
            <li>1 альт (место в группе)</li>
            <li>1 контрабас (концертмейстер группы)</li>
            <li>1 арфа</li>
        </ul>
        <br><br>

        <em>Группа деревянных духовых инструментов:</em>
        <ul>
            <li>1-й гобой (концертмейстер группы)</li>
            <li>2-й гобой (регулятор группы)</li>
            <li>1-й фагот (концертмейстер группы)</li>
        </ul>
        <br><br>

        <em>Группа медных духовых инструментов:</em>
        <ul>
            <li>2-я валторна (регулятор группы) </li>
            <li>3-я труба (регулятор группы) </li>
        </ul>
        <br><br>

        <em>Группа ударных инструментов:</em>
        <ul>
            <li>1 (концертмейстер группы) </li>
        </ul>
        <br><br>

        <ul>
            <li>Заработная плата от 60.000р. (по результатам собеседования и прослушивания).</li>
            <li>Арендное жилье (оплата найма компенсируется).</li>
            <li>Полный соц. пакет, включая оплату проезда к месту отдыха раз в два года.</li>
            <li>Различные социальные выплаты и премии.</li>
            <li>Возможность сольных выступлений с оркестром, дополнительной преподавательской деятельности.</li>
            <li>Международная гастрольная деятельность.</li>
            <li>Интересная творческая работа в молодом, развивающемся коллективе.</li>
        </ul>
        <br><br>

        Резюме и видеозаписи отправлять по эл. почте: <a href="mailto:erazhisht@gmail.com">erazhisht@gmail.com</a><br>
        или на моб.: <a href="tel:+79841341238">+7-984-134-12-38</a> (WhatsApp)
    </div>
</div>
<?php
require_once ROOT.'/includs/footer.php';
?>
 
</body>
</html>