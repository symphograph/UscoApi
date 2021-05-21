<?php
if(empty($_POST['year']))
    die();
$year = $_POST['year'] ?? date('Y');
$year = intval($year);

$sort = $_POST['sort'] ?? 0;
$sort = intval($sort);

$sorts = ['DESC', ''];

$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';


$qwe = qwe("
        SELECT
        anonces.concert_id as ev_id,
        anonces.hall_id,
        anonces.prog_name,
        anonces.sdescr,
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
        WHERE year(datetime) = '$year'
        ORDER BY anonces.datetime ".$sorts[$sort]);
        if(!$qwe or !$qwe->num_rows)
            die();

foreach($qwe as $q)
{
    $Anonce = new AnonceCard();
    $Anonce->clone($q);
    $Anonce->printItem();
}
