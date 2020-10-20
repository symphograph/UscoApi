<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/../includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$p_title = 'Тигран Ахназарян';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
    <?php CssMeta(['menu.css','index.css','menum.css', 'right_nav.css','tsa.css'])?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once $root.'/../includs/links.php';
require_once $root.'/../includs/header.php';
?>


<div class="content">

<div class="text">
<div class="p_title"><?php echo $p_title;?></div>
<div class="rcol"><img src="img/tsa1.jpg"/></div>

<div class="paragraph"><br>
    Художественный руководитель и главный дирижёр Южно-Сахалинского Камерного Оркестра.<br><br>
    Тигран Ахназарян окончил Ереванскую государственную консерваторию им. Комитаса по классу скрипки (профессор Сурен Ахназарян) и оперно-симфонического дирижирования (профессор Эмин Хачатурян).
    <br><br>Стажировался в Санкт-Петербургской государственной консерватории у легендарного профессора Ильи Мусина. Принимал участие в международном мастер классе выдающегося дирижёра современности Курта Мазура.
    <br><br>В возрасте 23 лет был приглашён в качестве художественного руководителя и главного дирижёра Национального Симфонического оркестра Республики Башкортостан, став самым молодым руководителем симфонического оркестра среди российских дирижёров.
    <br><br>В 2010 - 2012 гг. был главным дирижёром Государственного Театра оперы и балета Республики Коми (Россия), где дирижировал несколькими десятками оперных и балетных спектаклей.
    <br><br>В 2013 - 2016 гг. - художественный руководитель и главный дирижёр Дальневосточного Академического Симфонического оркестра, с которым выступал также в концертном зале
    Мариинского театра.
    <br><br>С 2016 г. - художественный руководитель и главный дирижёр Южно-Сахалинского камерного оркестра, с которым подготовил большое количество премьерных программ, отмеченных прессой и публикой как в России, так и за рубежом. Гастролировал в Австрии, Польше, Мальте, Сербии, Японии, Китае, Южной Корее, Украине, Казахстане, Армении. Дирижировал многими известными оркестрами, такими как Венский симфонический оркестр, Симфонический оркестр Мариинского театра. Симфонический оркестр Воеводины, Государственный Академический оркестр Республики Казахстан, Филармонический оркестр Армении, Филармонические оркестры Республики Карелия, г. Волгограда, г. Щецин, г. Харбин, г. Сочон, Камерный оркестр «Солисты Москвы» Юрия Башмета и многие др.
    <br><br>Дирижировал на крупнейших международных музыкальных фестивалях:
    <li>«Звёзды белых ночей» (Санкт-Петербург 2014),</li>
    <li>«Kunstendorf classic» Эмира Кустурицы (Сербия 2019),</li>
    <li>Фестиваль Юрия Башмета (Хабаровск 2014, 2015)</li>
    и др.
    <br><br>В качестве солистов с Тиграном Ахназаряном выступали Алёна Баева, Юрий Башмет, Маюко Камио, Маквала Касрашвили, Филипп Копачевский, Артур Назиуллин, Зураб Соткилава, Владислав Сулимский, Александр Тростянский, Игорь Фёдоров, Александр Чайковский и др.
    <br><br>В 2014 г. совместно с Маэстро Валерием Гергиевым в 2014 г. дирижировал концерт симфонического оркестра Мариинского театра и Дальневосточного академического симфонического оркестра. По приглашению Валерия Гергиева в феврале 2017 г. состоялся успешный дебют Тиграна Ахназаряна на Приморской сцене Мариинского театра с оперой «Макбет» Дж. Верди. В дальнейшем поставил первую оперу на Сахалине (Дж. Верди «Симон Бокканегра» в концертном исполнении), совместно с ЮСКО, хором и ведущими солистами Мариинского театра.
    <br><br>Основатель и художественный руководитель Дальневосточного музыкального фестиваля для детей и юношества «Звёзды Эвтерпы» 2015, 2017, 2019 гг.
    <br><br>Тигран Ахназарян обладает разнообразным репертуаром - от барочной до современной; симфонические произведения, инструментальные концерты, оперы, балеты, сочинения для камерного оркестра. Активно занимается просветительской деятельностью, постоянно выявляя и поддерживая молодые таланты. Приглашается в качестве члена жюри международных конкурсов.


</div>
</div>

<?php
require_once $root.'/../includs/footer.php';
?>
</body>
</html>