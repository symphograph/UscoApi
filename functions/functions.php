<?php
//Вывод селекта с сотрудниками
function pers_select($action,$pers_id)
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

function pers_select2($pers_id)
{
	$selected = '';
	?>
	
		<select class="fio fioselect" name="pers" autocomplete="off">
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
		
		<?php
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

//Проверяем попытки ввода sms кода
function TryCount( $mobile, $code)
{	
	$query = qwe("SELECT * FROM `sms` WHERE `mobile` = '$mobile' AND (`time` BETWEEN  NOW() - INTERVAL 15 MINUTE AND NOW())");
	if(mysqli_num_rows($query) == 1)
	{	
		foreach($query as $t)
		{
			$try = $t['try'];
			$needcode = $t['kod'];
		}
		if($try < 1)
		{
			//Попытки исчерпаны. Удаляем код из бд.
			qwe("DELETE FROM `sms` WHERE `mobile` = '$mobile'");
			return 'over';
		}
		if($try > 0 and $code != $needcode)
		{
			//Попытка была, но неудачная.
			qwe("UPDATE `sms` SET `try` = `try` -1 WHERE `mobile` = '$mobile'");
			return 'bad_try';
		}
		if($try > 0 and $code == $needcode)
		{
			qwe("DELETE FROM `sms` WHERE `mobile` = '$mobile'");
			return 'success';
		}
	}
	else
		//Попыток еще небыло
		return 'empty';
}

function printr($var) {
  echo '<pre>';
  print_r($var);
  echo '</pre>';
}

function CsrfInForm($form)
{
$csrf_token = random_str(32);
$_SESSION['csrf_'.$form] = $csrf_token;
?>
<input type="hidden" name="csrf_<?php echo $form;?>" value="<?php echo $csrf_token;?>"/>
<?php
}

function CsrfValid($form)
{
	if($_SESSION['csrf_'.$form] === $_POST['csrf_'.$form])
	{ 
		$_SESSION = array();
		return true;
	}
	else
	{
		$session_id = $_COOKIE['session_id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		qwe("INSERT INTO `error_log` 
		(`error_t_id`, `session_id`, `description`, `ip`, `time`)
		VALUES 
		('1', '$session_id', '$form', '$ip', NOW())");
		$_SESSION = array();
		return false;
	}
		
}

function is_Date($str){
    return is_numeric(strtotime($str));
}

function Comment($string)
{
	$string = strip_tags($string,'<br>');
	$string = preg_replace('/[^0-9a-zA-Zа-яА-ЯёЁ \,\.\(\)\]\[\_\:«»\-(?<br>)]/ui', '',$string);
	$string = trim($string);
	return($string);
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
	$string = preg_replace('/[^0-9a-zA-Zа-яА-ЯёЁ]/ui', '',$string);
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
		$img = $q['img'];
		if(empty($img))
		    $img = 'img/news/default_news_img.png';

        $img = $img.'?ver='.md5_file($img);
		$img = '<img src="'.$img.'" width="260px"/>';
		$ntitle = $q['new_tit'];
		$new_id = $q['new_id'];
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
	if(empty($_COOKIE['identy']))
	{
	$identy = random_str(12);
	$cooktime = $unix_time+60*60*24*365*5;
	setcookie('identy',$identy,$cooktime,'/','',true,true);
	$query = qwe("
	INSERT INTO `identy`
	(`identy`, `ip`, `time`, `last_ip`, `last_time`)
	VALUES
	('$identy','$ip','$datetime','$ip','$datetime')
	");	
	}else
	{
		$identy = OnlyText($_COOKIE['identy']);
		if(iconv_strlen($identy) != 12)
		{
			return false;
		}
		$query = qwe("
			SELECT * FROM `identy`
			WHERE `identy` = '$identy'
			");
		if(mysqli_num_rows($query) == 1)
		{
			$query = qwe("
			UPDATE `identy` SET
			`last_ip` = '$ip',
			`last_time` = '$datetime'
			WHERE `identy` = '$identy'
			");
		}else
		{
			setcookie ("identy", "", time() - 3600);
			return false;
		}
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
	extract($q);
	$complited = $recorded = false;
	$prrows = ['','','Вход свободный','Билеты в продаже','Вход по пригласительным','Билеты в продаже'];
	global $myip;
	if((!$myip) and $ev_id < 4) return false;
	

	
	

	$bg = 'style="background-image: url(img/afisha/'.$img.')"' ?? '';
	$bg = '';


	$prrow = $prrows[$pay];
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
        <a href="<?php echo $map;?>" target="_blank"><?php echo $hall_name;?></a>
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
		$qwe = qwe( "SELECT * FROM video ORDER BY v_date");
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
?>