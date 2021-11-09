<?php
//header('Content-type: image/jpg');
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
if(!$cfg->myip)
    die();

?><meta charset="utf-8"><?php
printr(get_browser());
echo pathinfo('includs/config.php',PATHINFO_FILENAME);
die();
//require dirname($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php';
//use Intervention\Image\ImageManagerStatic as Image;

//$an = new AnonceCard();
//$an->byId(118);
//printr($an);
$qwe = qwe("
SELECT
anonces.concert_id as ev_id,
anonces.hall_id,
anonces.prog_name,
anonces.sdescr,
anonces.img,
anonces.topimg,
/*anonces.aftitle,*/
anonces.datetime,
anonces.pay,
anonces.age,
anonces.ticket_link,
halls.hall_name,
halls.map
FROM
anonces
INNER JOIN halls ON anonces.hall_id = halls.hall_id
WHERE anonces.concert_id = 100
ORDER BY anonces.datetime DESC 
");
$qwe = $qwe->fetchAll(PDO::FETCH_CLASS,"Anonce");
foreach($qwe as $q) {
    $Anonce = new AnonceCard();
    $Anonce->clone($q);
    $image = new Imagick('img/afisha/'.$Anonce->img);
    $image->transformImageColorSpace(\Imagick::COLORSPACE_SRGB);
    //$image->thumbnailImage(480,0);
    $image->resizeImage(480,0,0,1);
    try {
        $image->writeImage('img/posters/480/' . $Anonce->img);
    } catch (ImagickException $e) {
        echo $e;
    }


    //echo $image;
   // printr($image);
    //$Anonce->printItem();
    break;
}

?>
<img src="img/posters/480/<?php echo $Anonce->img?>">

