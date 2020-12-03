<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$p_title = 'Александр Гаврилович  Зражаев (1944 - 2017)';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
<link href="css/menu.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo $ver?>" rel="stylesheet">
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
<div class="p_title"><?php echo $p_title;?></div>
<p>Художественный руководитель и дирижер Южно-Сахалинского камерного оркестра (1999 - 2016)</p>

<p>Заслуженный работник культуры Российской Федерации (2006)</p>

<br><br>
Александр Зражаев родился 1 января 1944 года в городе Уссурийске (Приморский край). Родители к музыке отношения не имели: папа – железнодорожник, мама – домохозяйка. Путь в мир музыки начал в шестилетнем возрасте в Красноярске, поступив на скрипичное отделение музыкальной школы. В 1957 году семья переехала в город Южно-Сахалинск, где Александр Гаврилович продолжил обучение в Южно-Сахалинской Центральной музыкальной школе, а потом и в Сахалинском музыкальном училище. После заочно прошел обучение на отделении оркестрового факультета в Государственной консерватории имени Курмангазы в Казахстане. 
<br><br>
В 1999 году вместе с коллегами по ансамблю основал первый в области профессиональный оркестр, Южно-Сахалинский камерный оркестр, где работал художественным руководителем и дирижером до 2016 года.
<br><br>

«Я всегда мечтал, – говорил Александр Гаврилович, – чтобы столица области стала и ее музыкальным центром. Она должна стать неотъемлемой частью социальной политики. А посещение концерта классической музыки – такой же настоятельной потребностью для любого человека, как приведение себя утром в порядок».
<br><br>

Александр Гаврилович – лауреат премии Сахалинского фонда культуры, имеет звание «Заслуженный работник культуры Сахалинской области». Он награжден: тремя медалями, Почетными грамотами Министерства культуры РФ, губернатора Сахалинской области, а также Всероссийского и Сахалинского фондов культуры. Камерный оркестр под его управлением – дипломант фестивалей «Ступени» и «Высокая музыка на Сахалине», который проводился под патронатом лауреата международных конкурсов Дмитрия Когана.
<br><br>
В сентябре 2016 года Правительство Сахалинской области отметило Александра Зражаева почетным знаком первой степени "За заслуги перед городом Южно-Сахалинском".
<br><br>
Александр Зражаев скончался в 2017 году после продолжительной болезни.

</div>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
</body>
</html>