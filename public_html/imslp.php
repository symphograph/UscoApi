<?php 
require_once 'includs/ip.php';
require_once 'includs/config.php';
require_once 'functions/functions.php';
if(!$myip) die();

/*
 Array
        (
            [id] => "Los ojos tiernos" (Esnaola, Juan Pedro)
            [type] => 2
            [parent] => Category:Esnaola, Juan Pedro
            [intvals] => Array
                (
                    [composer] => Esnaola, Juan Pedro
                    [worktitle] => "Los ojos tiernos"
                    [icatno] => 
                    [pageid] => 893446
                )

            [permlink] => https://imslp.org/wiki/"Los_ojos_tiernos"_(Esnaola,_Juan_Pedro)
        )163000
*/
$i2 = 0;
for($i=89341; $i<163000; $i=$i+1000)
{

	$res = file_get_contents('https://imslp.org/imslpscripts/API.ISCR.php?account=worklist/disclaimer=accepted/sort=id/type=2/start='.$i.'/retformat=json/limit=1');
	//var_dump($res);
	$res = json_decode($res);
	var_dump($res); die();
	$cnt = 0;
	$cnt = count((array)$res);
	$cnt = intval($cnt);
	echo $i.'<br>';
	/*if($cnt>1)
	ImslpData($res);
	else*/
	break;
	$i2++;
	sleep(1);
}
echo $i2;
function ImslpData($res)
{
	
	foreach ($res as $k=>$v)
	{
		ExtractObject($v);
	}
}

function ExtractObject($v)
{
	global $dbLink;
	extract((array)$v);
		
	if(isset($intvals))
		extract((array)$intvals);
	else 
		return false;

	$pageid = intval($pageid);
	$composer = mysqli_real_escape_string($dbLink,$composer);
	$worktitle = mysqli_real_escape_string($dbLink,$worktitle);
	$icatno = mysqli_real_escape_string($dbLink,$icatno);
	
	qwe("
	INSERT INTO `works` 
	(pageid,composer,worktitle,icatno)
	VALUES 
	('$pageid','$composer','$worktitle','$icatno')
	");

	echo $composer;
	//echo $pageid;
	echo '<br>';
}
?>

<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Документ без названия</title>
</head>

<body>
</body>
</html>