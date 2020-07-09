<?php 
include_once 'includs/ip.php';
include_once 'includs/config.php';
include_once 'functions/functions.php';
include_once 'includs/check.php';
$ver = random_str(8);
$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
$evid = intval($_GET['evid']);
$ptypes = ['','','Вход свободный','Онлайн продажа завершена','Вход по пригласительным'];
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
halls.hall_name,
halls.map
FROM
anonces
INNER JOIN halls ON anonces.hall_id = halls.hall_id
WHERE `concert_id` = '$evid'
");
foreach($query as $q)
{
	extract($q);
	//$description = $q['description'];
	//$prog_name = $q['prog_name'];
	//$aftitle = $q['aftitle'];
	//$img = $q['img'];
	//$datetime = $q['datetime'];
	$date = date('d.m.Y',strtotime($datetime));
	$time = date('H:i',strtotime($datetime));
	$description = '<p>'.$date.' '.$time.'</p>'.$description;
	$imglink = $host.'img/afisha/'.$img;
	$size = getimagesize($imglink);
	$width = $size[0];
	$height = $size[1];
	//$bg = 'style="background-image: url(img/afisha/'.$q['img'].')"' ?? '';
    $bg = '';
    $prrow = '';
    $byebtn = $prrow;
}
?>
<html>
<head>
 <meta charset="utf-8">
  <title><?php echo $prog_name?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
<link href="css/menu.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menu.css');?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/index.css');?>" rel="stylesheet">

<link href="css/event.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/event.css');?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menum.css');?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/right_nav.css');?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://vk.com/js/api/openapi.js?154" type="text/javascript"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
    <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
  <meta property="og:url"           content="<?php echo $host.'event.php?evid='.$ev_id?>" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="<?php echo $prog_name?>" />
  <meta property="og:description"   content="<?php echo $date.' '.$time?>" />
  <meta property="og:image"         content="<?php echo $imglink?>" />
  <meta property="og:image:width"         content="<?php echo $width?>" />
  <meta property="og:image:height"         content="<?php echo $height?>" />
</head>
<body>
<?php
FacebookScript();
include 'includs/links.php';
include 'includs/header.php';	
?>
<div class="content">
    <div class="eventsarea">
        <div class="eventboxl">
            <div class="eventbox" <?php echo $bg;?>>
                <img src="img/afisha/<?php echo $q['img'];?>?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/img/afisha/'.$q['img']);?>" width="100%">
            </div>
            <div class="eventboxin">
                <div class="text">
                    <div class="eventcol"><?php echo $aftitle;?></div>
                    <p><b><?php echo $prog_name ?></b></p>
                    <p><?php echo $description?></p>
                    <?php echo $byebtn;?>
                </div>
                <br>
                <div class="share-buttons">
                    <div class="fbb">
                        <!-- Put this script tag to the <head> of your page -->
                        <script type="text/javascript" src="https://vk.com/js/api/share.js?95" charset="windows-1251"></script>

                        <!-- Put this script tag to the place, where the Share button will be -->
                        <script type="text/javascript">
                        document.write(VK.Share.button(false,{type: "round", text: "Поделиться"}));
                        </script>
                    </div>
                    <div class="fbb">
                        <div class="fb-share-button"
                             data-href="<?php echo $host.'event.php?evid='.$ev_id?>"
                             data-layout="button_count">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'includs/footer.php';
?>
</body>
</html>