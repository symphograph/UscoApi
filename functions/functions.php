<?php
//Вывод селекта с сотрудниками
function pers_select($action,int $pers_id)
{
	$selected = '';
	?>
	<form name="pers_select" id="pers_select" method="post" action="<?php echo $action;?>">
		<select class="fio fioselect" name="pers" autocomplete="off" onchange="this.form.submit()">
	<?php
	if(!isset($pers_id) or $pers_id == 0)
		{
		?>
		<option class="fio" value="0" selected >Выберите сотрудника</option>
		<?php
		}
	
	$query = qwe( "SELECT * FROM `personal` ORDER by `last_name`");
			
	foreach ($query as $k)
	{
		if(isset($pers_id) and $pers_id == $k['id'])
			$selected = 'selected';
		?>
		<option value="<?php echo $k['id'];?>" <?php echo $selected;?> ><?php echo $k['last_name'].' '.$k['name'].' '.$k['patron'];?></option>
		<?php
		$selected = '';
	}
		?>
		</select></form>
		<?php
}

function pers_select2(int $pers_id = 0)
{
	$selected = '';

	?><select class="fio fioselect" name="pers_id" id="pers_select" autocomplete="off"><?php

	if(!$pers_id)
		{
		?><option class="fio" value="0" selected >Выберите сотрудника</option><?php
		}
	
	$query = qwe2( "
    SELECT * FROM `personal` 
    WHERE place_id 
    ORDER by `last_name`
    ");
			
	foreach ($query as $k)
	{
		if(isset($pers_id) and $pers_id == $k['id'])
			$selected = 'selected';

		?><option value="<?php echo $k['id'];?>" <?php echo $selected;?> ><?php echo $k['last_name'].' '.$k['name'].' '.$k['patron'];?></option><?php

		$selected = '';
	}
    ?></select><?php
}

//Для числительных. (год, года, лет)
function number($n, $titles) {
$n = intval($n);	
  $cases = array(2, 0, 1, 1, 1, 2);
  return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}

/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

//Опшенсы простого селекта
function SelectOpts($query, $col_val, $col_name, $sel_val, $defoult)
{	$selected = '';
	echo '<option value="0">'.$defoult.'</option>';
	foreach($query as $q)
	{
		if($q[$col_val] == $sel_val)
			$selected = 'selected';
		echo '<option value="'.$q[$col_val].'" '.$selected.'>'.$q[$col_name].'</option>';
		$selected = '';
	}
}

function CssMeta(array $css_arr)
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    foreach ($css_arr as $css)
    {
        ?><link href="css/<?php echo $css?>?ver=<?php echo md5_file($root.'/css/'.$css)?>" rel="stylesheet"><?php
    }
}

function printr($var) {
  echo '<pre>';
  print_r($var);
  echo '</pre>';
}

function is_Date($str){
    return is_numeric(strtotime($str));
}

function ru_date($format, $date = false) {
	setlocale(LC_TIME, 'ru_RU.UTF-8');
	if (!$date) {
		$date = time();
	}
	if ($format === '') {
		$format = '%e&nbsp;%bg&nbsp;%Y';
	}
	$months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
	$format = preg_replace("~\%bg~", $months[date('n', $date)], $format);
	return strftime($format, $date);
}

function OnlyText($string)
{
	$string = trim($string);
	$string = preg_replace('/[^0-9a-zA-Zа-яА-ЯёЁ_]/ui', '',$string);
	$string = trim($string);
	return($string);
}

function NewsCol($qwe = false)
{
	global $myip;
?>
	<div class="newscol">
	<div class="ntitle"><b>НОВОСТИ ОРКЕСТРА</b></div><br>
	<?php
	if(!$qwe)
	$qwe = qwe("
	SELECT * from `news`
	ORDER BY `date` DESC
    limit 5
	");
	foreach($qwe as $q)
	{
        $new_id = $q['new_id'];

	    if (!$myip and $new_id < 9)
	        continue;

		$img = $q['img'];
		if(empty($img))
		    $img = 'img/news/default_news_img.svg';

        $img = $img.'?ver='.md5_file($img);
		$img = '<img src="'.$img.'" width="260px"/>';
		$ntitle = $q['new_tit'];

		//if((!$myip) and $new_id == 17) continue;
		$ntext = $q['text'];
		?>
		<br><hr><br>
		<div class="narea">
		    <div class="nimg_block">
                <div>
                    <a href="new.php?new_id=<?php echo $new_id;?>"/>
                    <?php echo $img?>
                    </a>
                </div>
            </div>


            <div class="tcol">
                <div class="ntitle">
                    <a href="new.php?new_id=<?php echo $new_id;?>">
                     <b><?php echo $ntitle;?></b>
                    </a>
                </div>
                <br>
                <a href="new.php?new_id=<?php echo $new_id;?>">
                <?php echo $ntext;?>
                </a>
                <br><br>
                <?php
                    $ndate = strtotime($q['date']);
                    $ndate = ru_date('',$ndate);
                ?>
                <span class="ndate"><?php echo $ndate;?></span>
                <br>
            </div>
		</div><?php
	}
	?>
	<br><hr><br>
	</div>
<?php
}

function Metka($ip)
{
	//проверяем, помечен ли юзер
	//если не помечен, метим
	$unix_time = time();
	$datetime = date('Y-m-d H:i:s',$unix_time);
    $cooktime = $unix_time+60*60*24*365*5;

	if(empty($_COOKIE['identy']))
	{
        $identy = random_str(12);

        setcookie('identy',$identy,$cooktime,'/','',true,true);
        $qwe = qwe("
        INSERT INTO `identy`
        (`identy`, `ip`, `time`, `last_ip`, `last_time`)
        VALUES
        ('$identy','$ip','$datetime','$ip','$datetime')
        ");

        if(!$qwe)
            return false;
        else
            return $identy;
	}

    $identy = OnlyText($_COOKIE['identy']);
    if(iconv_strlen($identy) != 12)
    {
        return false;
    }
    $query = qwe("
        SELECT * FROM `identy`
        WHERE `identy` = '$identy'
        ");
    if($query and $query->num_rows == 1)
    {
        qwe("
        UPDATE `identy` SET
        `last_ip` = '$ip',
        `last_time` = '$datetime'
        WHERE `identy` = '$identy'
        ");
        setcookie('identy',$identy,$cooktime,'/','',true,true);
    }else
    {
        setcookie ("identy", "", time() - 3600*24*360*10);
        return false;
    }

	return $identy;
}

function myUrlEncode($string) {
    $entities = ['%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D'];
    $replacements = ['!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]"];
    return str_replace($entities, $replacements, urlencode($string));
}

function EvdateFormated($datetime)
{
    $evdate = strtotime($datetime);
	$evdateru = ru_date('%e&nbsp;%bg&nbsp',$evdate);
	$evtime = date('H:i',$evdate);
    if(date('Y',$evdate) == date('Y',time()))
	    return $evdateru.' в '.$evtime;
	else
	    return date('d.m.Y в H:i',$evdate);
}

function ConcertItem($q)
{
    /**
     * @var $pay
     * @var $ev_id
     * @var $ticket_link
     */
	extract($q);
	$complited = $recorded = false;
	$prrows = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];
	global $myip;
	if((!$myip) and $ev_id < 4) return false;


	//$bg = 'style="background-image: url(img/afisha/'.$img.')"' ?? '';
	$bg = '';

	$prrow = $prrows[$pay] ?? '';
	$byebtn = '
	<div><br>
	<p><span>'.$prrow.'</span></p><br>
	<p><a href="event.php?evid='.$ev_id.'" class="tdno"><div class="bybtn"><span class="bybtntxt">Подробно</span></div></a></p>
	</div>';
	
	
	if($pay == 5 and $_SERVER['SCRIPT_NAME'] != '/complited.php')
	{

		$byebtn = '<div><br>
		<p><span>'.$prrow.'</span></p><br>
		<p><a href="'.$ticket_link.'" class="tdno"><div class="bybtn"><span class="bybtntxt">Купить онлайн</span></div></a></p>
		</div>';
	}

	if($_SERVER['SCRIPT_NAME'] == '/complited.php')//TO_DO!!!
	{
		if($youtube_id)
		{
			$byebtn = '<div><br>
			<p><span>'.$prrow.'</span></p><br>
			<p>
                <a href="https://www.youtube.com/watch?v='.$youtube_id.'" class="tdno">
                    <div class="bybtn">
                        <span class="bybtntxt">Смотреть видео</span>
                    </div>
                </a>
			</p>
			</div>';
		}
	}
	
	?><div class="eventbox tdno" <?php echo $bg;?>>

	<div class="pressme">
	<div>
        <div class="affot">
        <img src="<?php echo 'img/afisha/'.$topimg;?>?ver=<?php echo md5_file('img/afisha/'.$topimg)?>" width="100%" height="auto">

                <?php
                if($age)
                {
                    ?><div class="age"><?php echo $age?>+</div><?php
                }
                ?>

        </div>
        <br>
        <div class="evdate">
        <?php echo EvdateFormated($datetime)?>
        </div>
        <a href="<?php echo $map;?>" class="hall_href" target="_blank"><?php echo $hall_name;?></a>
	</div>

	
		<div class="aftext">
			<a href="event.php?evid=<?php echo $ev_id;?>" class="tdno">
				<div class="evname"><?php echo $prog_name;?></div>
				<br>
				<div class="sdescr"><?php echo $sdescr?>
					<br><br>
					Художественный руководитель  и главный дирижер - <b>Тигран Ахназарян</b>.
				</div>
			</a>

		</div>
		<div class="downbox">
            <div class="tdno"><?php echo $byebtn;?></div>
        </div>
	
	</div>
	</div>
<?php
}

function VideoItem($youtube_id) //Подразумевается youtube. Иные не преюполагаются.
{
	//width="360" 
	//height="180"
	?>
	<div class="vitem">
	<iframe 
			width="100%"
			height="100%"
			src="https://www.youtube.com/embed/<?php echo $youtube_id?>"
			frameborder="0"
			allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
			allowfullscreen>
	</iframe>
	</div>
	<?php
}

function VideoItems($qwe = false)
{
	if(!$qwe)
		$qwe = qwe( "SELECT * FROM video ORDER BY v_date DESC");
	?><div class="vidarea"><?php
	foreach($qwe as $q)
	{
		extract($q);
		VideoItem($youtube_id);
	}
	?></div><?php
}

function FacebookScript()
{
    ?>
    <div id="fb-root"></div>
    <script async defer
    crossorigin="anonymous"
    src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v7.0"
    nonce="RyPHx5MY">
    </script>
    <?php
}

function FacebookCol()
{
    ?>
    <div class="fb-page"
     data-href="https://www.facebook.com/SakhalinSymphony/"
     data-tabs="timeline"
     data-width=""
     data-height=""
     data-small-header="false"
     data-adapt-container-width="true"
     data-hide-cover="false"
     data-show-facepile="false">
        <blockquote cite="https://www.facebook.com/SakhalinSymphony/" class="fb-xfbml-parse-ignore">
            <a href="https://www.facebook.com/SakhalinSymphony/">Sakhalin Symphony Orchestra</a>
        </blockquote>
    </div>

    <?php
}

function CookWarning()
{
    if(!empty($_COOKIE['cookok']))
        return false;
    ?>
    <script type="text/javascript" src="js/jquery-latest.js?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/js/jquery-latest.js')?>"></script>
    <script type="text/javascript" src="js/cook.js?ver=<?php echo md5_file($root.'/js/cook.js')?>"></script>
    <div class="cookdiv">
        <div class="cooktext">Сайт использует файлы Cookies. Они помогают ему стать лучше.</div>
        <div>
            <div class="bybtn" id="cookconfirm">
                <span class="bybtntxt">Хорошо</span>
            </div>
        </div>
    </div>
<?php
}

function SetToken($identy)
{
    $qwe = qwe("
    SELECT * FROM identy 
    WHERE identy = '$identy'
    AND last_time >= (NOW() - INTERVAL 10 MINUTE) 
    ");
    if($qwe and $qwe->num_rows)
    {
        $q = mysqli_fetch_object($qwe);
        return $q->token;
    }

    $token = random_str(12);
    qwe("
    UPDATE identy 
    SET token = '$token'
    WHERE identy = '$identy'
    ");
    return $token;
}
?>