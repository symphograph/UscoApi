<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$p_title = 'История';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','menum.css', 'right_nav.css'])?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
?>


<div class="content">

<div class="text">
<div class="p_title"><?php echo $p_title;?></div><br>
    Южно-Сахалинский Камерный Оркестр основан в 1999 году заслуженным
    работником культуры Сахалинской области, скрипачом <a href="zag.php">Александром Зражаевым</a>. За 20
    лет своего существования коллектив стал неотъемлемой частью культурной жизни
    Сахалинской области и Дальневосточного региона. С 2016 года художественный
    руководитель и главный дирижер оркестра <a href="tsa.php">Тигран Ахназарян</a>.
    <br><br>ЮСКО проводит большую концертную и просветительскую деятельность,
    насчитывающую более 700 выступлений, подготовлено много совместных концертных
    выступлений с музыкантами из России, Японии, Южной Кореи, США, Израиля. Оркестр
    играет множество премьерных на Сахалине программ. Успешно гастролировал в Японии,
    Южной Корее, Китае, Сербии, выступает на различных международных музыкальных
    фестивалях и концертных турне.
    <br><br>В 2019г. оркестр под руководством Тиграна Ахназаряна
    был приглашен на гала-концерт престижного фестиваля режиссера Эмира Кустурицы
    «Kustendorf Classic» в Сербии. Также является участником значимых всероссийских и
    международных кинофестивалей, проводимых на острове: «Край света» и «Утро Родины».
    <br><br>С оркестром выступали солисты и дирижёры мирового уровня: Борис Андрианов,
    Нарек Ахназарян, Ким Джон Док, Ким Нан Се, Дмитрий Коган, Филипп Копачевский,
    Сергей Кравченко, Артур Назиуллин, Хироко Нинагава, Константин Орбелян, Татьяна
    Павловская, Николай Петров, Айлен Притчин, Кирилл Солдатов, Владислав Сулимский,
    Александр Тростянский, Станислав Трофимов, Игорь Федоров, Аяко Ясуда, Гия Яшвили.
    <br><br>Под руководством Тиграна Ахназаряна коллектив проводил совместные концерты с
    симфоническим оркестром Мариинского театра, симфоническим оркестром Хакодате
    (Япония), симфоническими оркестрами Сеула, Сочона, «KYDO» (Южная Корея).
    ЮСКО под управлением Тиграна Ахназаряна, совместно с ведущими солистами,
    хором и артистами оркестра Приморской сцены Мариинского театра осуществил первую
    оперную постановку на Сахалине, оперу Дж. Верди «Симон Бокканегра». Состоялась
    сахалинская премьера детской антивоенной оперы «Брундибар» Ганса Краса. Впервые
    прозвучал «Реквием» В.А. Моцарта, совместно с детским симфоническим оркестром
    Южной Кореи KYDO.
    <br><br>Особое внимание уделяется детским и молодежным образовательным программам.
    Подготовлены и исполнены эксклюзивные музыкально-литературные проекты Тиграна
    Ахназаряна: «Щелкунчик» (П.И Чайковский - Э.А. Гофман), «Чиполлино» (К. Хачатурян –
    Дж. Родари), «Петя и Волк» (С. Прокофьев). В 2017г. был проведен II Дальневосточный
    музыкальный фестиваль для детей и юношества, в котором принимали участие одаренные
    дети солисты-дальневосточники.
    <br><br>ЮСКО проводит множество ярких тематических концертов, среди которых «Окно в
    Европу», «Путеводитель по Америке», «Фейерверк танцев», «В мире сказок», «Опера-
    Гала» и многие другие, которые получают восторженный отклик публики.
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>