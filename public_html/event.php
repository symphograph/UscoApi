<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/includs/check.php';
$ver = random_str(8);
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
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

	$date = date('d.m.Y',strtotime($datetime));
	$time = date('H:i',strtotime($datetime));
	$description = '<br><p><b>'.$date.' '.$time.'</b></p><br>'.$description;
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
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $prog_name?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','event.css','menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
require_once $root.'/includs/links.php';
require_once $root.'/includs/header.php';
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

                    <br><br>
                    Справки по тел:<br>
                    <div class="tel"><a href="tel:+74242300518">+7-4242-300-518</a></div>
                    <br>
                    <div class="tel"><a href="tel:+79624163689">+7-962-416-36-89</a></div>
                    <br>
                </div>
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
require_once $root.'/includs/footer.php';
?>
</body>
</html>