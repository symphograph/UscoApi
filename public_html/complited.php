<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/includs/check.php';

$p_title = 'Прошедшие концерты';
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


</head>

<body>


<?php
require_once $root.'/includs/links.php';
require_once $root.'/includs/header.php';
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>

<!--<div class="ubis"><b>XX ЮБИЛЕЙНЫЙ СЕЗОН</b></div>-->
<div class="content">

<div class="eventsarea">
<div class="p_title"><?php echo $p_title;?></div>

<?php
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
halls.map,
video.youtube_id
FROM
anonces
INNER JOIN halls ON anonces.hall_id = halls.hall_id
LEFT JOIN video ON anonces.concert_id = video.concert_id
WHERE datetime < NOW()
ORDER BY anonces.datetime DESC
");

?><div class="gridarea"><?
foreach($query as $q)
{
    ConcertItem($q);

}

?></div>

</div>
</div>
<?php
require_once $root.'/includs/footer.php';
?>

</body>
</html>