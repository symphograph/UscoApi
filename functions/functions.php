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

function CssMeta(array $css_arr)
{
    $root = $_SERVER['DOCUMENT_ROOT'];
    foreach ($css_arr as $css)
    {
        ?><link href="/css/<?php echo $css?>?ver=<?php echo md5_file($root.'/css/'.$css)?>" rel="stylesheet"><?php
    }
}

function printr($var) {
    global $cfg;
    if(!$cfg->myip && !$cfg->server_ip)
        return;
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function ru_date($format, $date = false) {
	setlocale(LC_TIME, 'ru_RU.UTF-8');
	if (!$date) {
		$date = time();
	}
	if ($format === '') {
		$format = 'd %bg Y';
	}

	$months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
    $n = date('n', $date);
	$format = preg_replace("~\%bg~", $months[$n], $format);
    return date($format, $date);
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

?>


        <?php
        if(!$qwe)
        $qwe = qwe("
        SELECT * from `news`
        WHERE `show` in (1,3)
        ORDER BY `date` DESC
        limit 5
        ");
        foreach($qwe as $q)
        {
            $q = (object) $q;
            $Item = new NewsItem();
            $Item->byQ($q);
            echo $Item->PrintItem();
        }
        ?>
        <br><br>
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
        echo Video::getVitem($youtube_id);
		//VideoItem($youtube_id);
	}
	?></div><?php
}

function CookWarning()
{
    if(!empty($_COOKIE['cookok']))
        return false;
    ?>
    <script type="text/javascript" src="js/jquery-latest.js?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/js/jquery-latest.js')?>"></script>
    <script type="text/javascript" src="js/cook.js?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/js/cook.js')?>"></script>
    <div class="cookdiv">
        <div class="cooktext">Сайт использует <a href="pdn.php">файлы Cookies</a>. Они помогают ему стать лучше.</div>
        <div>
            <div class="bybtn" id="cookconfirm">
                <span class="bybtntxt">Хорошо</span>
            </div>
        </div>
    </div>
<?php
}

function GetNextFreeFeedMailId()
{
    $qwe = qwe("
    SELECT max(`msg_id`) as `max`
    FROM `feed_mails`
    ");
    if(!$qwe or $qwe->num_rows != 1)
        return 1;

    $q = $qwe->fetchObject();

    return $q->max + 1;
}

function jsFile($file)
{
    ?><script type="text/javascript" src="<?php echo 'js/'.$file.'?ver='.md5_file($_SERVER['DOCUMENT_ROOT'].'/js/'.$file)?>"></script><?php
}

function anonceYears() : array
{
    $years = [];
    $qwe = qwe("
        SELECT year(datetime) as year 
        FROM anonces 
        GROUP BY year
        ORDER BY year DESC 
        ");
    if(!$qwe or !$qwe->rowCount()) {
        return $years;
    }

    foreach ($qwe as $q) {
        $years[] = $q['year'];
    }

    return $years;
}

function newsYears() : array
{
    $years = [];
    $qwe = qwe("
        SELECT year(date) as year 
        FROM news 
        GROUP BY year
        ORDER BY year DESC 
        ");
    if(!$qwe or !$qwe->rowCount()) {
        return $years;
    }

    foreach ($qwe as $q) {
        $years[] = $q['year'];
    }

    return $years;
}

function selectYear(array $years)
{
    ?>
    <select name="year" id="yearFilter">
        <?php
        foreach ($years as $k => $y) {
            $sel = '';
            if ($y == date('Y'))
                $sel = ' selected ';
            ?>
            <option value="<?php echo $y ?>"<?php echo $sel ?>> <?php echo $y ?> </option><?php
        }
        ?>
    </select>
    <?php
}

function cors() {

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    //printr($_SERVER['REQUEST_METHOD']);
    //die();
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}

function curl($plink, array $data = [])
{
    global $cfg;
    $data['apiKey'] = $cfg->apiKey;
    //printr($data);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_FAILONERROR, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // allow redirects
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); // times out after 4s
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // return into a variable
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_URL, $plink);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 GTB6");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $somepage = curl_exec($curl);
    //print_r($somepage);
    curl_close($curl);
    return $somepage;
}

function getRequestHeaders() : array
{
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}