<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<meta name="proculture-verification" content="9974889fb39244589ef78eb3c3879433" />
<?php
$p_title = 'Афиши';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
<?php CssMeta(['menu.css','index.css','afisha.css','menum.css', 'right_nav.css'])?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>-->
</head>

<body>


<?php
//FacebookScript();
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>

<!--<div class="ubis"><b>XX ЮБИЛЕЙНЫЙ СЕЗОН</b></div>-->
<div class="content">

<div class="eventsarea">
<?php
$prrows = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];

$query = qwe("
SELECT
anonces.concert_id as ev_id,
anonces.hall_id,
anonces.prog_name,
anonces.sdescr,
anonces.description,
anonces.img,
anonces.topimg,
anonces.aftitle,
anonces.datetime,
anonces.pay,
anonces.age,
anonces.ticket_link,
halls.hall_name,
halls.map
FROM
anonces
INNER JOIN halls ON anonces.hall_id = halls.hall_id
WHERE /*anonces.concert_id > 3 AND*/ datetime >= NOW()
ORDER BY anonces.datetime
");

if(!$query or $query->num_rows == 0)
{
    ?>
    <p>Аносов нет.</p>
    <p><a href="posters.php"/><span class="bybtntxt">Прошедшие концерты</span></a></p>
    <?php
}
?><div class="gridarea"><?
foreach($query as $q)
{
    ConcertItem($q);
}
?></div><?php


?>
</div>


</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
 
</body>
</html>