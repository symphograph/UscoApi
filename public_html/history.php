<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/includs/check.php';
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
require_once $root.'/includs/links.php';
require_once $root.'/includs/header.php';
?>


<div class="content">

<div class="text">
<div class="p_title"><?php echo $p_title;?></div>
Южно-Сахалинский камерный оркестр единственный профессиональный оркестр, созданный на Сахалине. В составе оркестра более <a href="stuff.php">40 музыкантов</a>.
<br><br>

Основал оркестр Заслуженный работник культуры России, скрипач и дирижер <a href="zag.php">Александр Зражаев</a>
<br><br>
Художественным руководителем и главным дирижером оркестра является российский дирижер <a href="zag.php">Тигран Ахназарян</a>
<br><br>
За 17 лет своего существования Южно-Сахалинский камерный оркестр стал ярким явлением в культурной жизни Сахалинской области и Тихоокеанского региона. Оркестр ведет большую концертную и просветительскую деятельность. Концерты коллектива имеют большой общественный резонанс, пользуются успехом у слушателей.
<br><br>

Коллективом осуществляются международные творческие проекты, которые развивают сотрудничество с музыкантами разных стран и вносят весомый вклад в укрепление международных культурных связей между Россией, Японией, Китаем, Южной Кореей. Подготовлены совместные программы со многими музыкантами из России, Японии, Южной Кореи, США, Израиля. С оркестром выступали солисты и дирижеры мирового уровня: пианист Николай Петров, вокалистка Энсил Канг (Южная Корея), скрипачи Сергей Кравченко, Гия Яшвили (Австрия), Дмитрий Коган, виолончелист Борис Андрианов, трубач Кирилл Солдатов, дирижеры Константин Орбелян (США), Ким Нан Се, Ким Джон Док (Южная Корея), Накадзима Масаюки (Япония). Сложились теплые дружеские связи и совместные выступления с Хакодатским симфоническим оркестром (Япония), Симфоническим оркестром Сеула и Сочонским филармоническим оркестром (Южная Корея). 
<br><br>

Южно-Сахалинский камерный оркестр успешно гастролирует в Японии, Южной Корее, выступая также на различных музыкальных фестивалях. Имеет почетные звания Лауреата фестиваля "Высокая музыка на Сахалине" (2005), Лауреата фестиваля "Ступени", проведенного под патронажем губернатора Сахалинской области (2006), Лауреата премии города Южно-Сахалинска (2008) и Лауреата премии Губернатора Сахалинской области (2009).
<br><br>

За свою творческую деятельность Южно-Сахалинский камерный оркестр дал более 700 камерных и симфонических концертов. 
<br><br>
Оркестр ведет большую концертную деятельность, выступая при постоянных аншлагах и приобщая слушателей к великим творениям мировой музыки в области классического музыкального искусства, воспитанию духовных и нравственных традиций.
<br>
</div>
</div>
<?php
require_once $root.'/includs/footer.php';
?>
</body>
</html>