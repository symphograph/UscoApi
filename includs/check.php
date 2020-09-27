<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/includs/ip.php';
require_once $root.'/includs/config.php';
require_once $root.'/functions/functions.php';
$usersess = '';
$spect_id = '';
$admin = false;
$tester = false;
if(!empty($_COOKIE['usersess']))
{
	$usersess = $_COOKIE['usersess'];
	$usersess = OnlyText($usersess);
	$usersess = mysqli_real_escape_string($dbLink,$_COOKIE['usersess']) ;
	$query = qwe("
	SELECT
	`spsessions`.`usersess`,
	`spsessions`.`spect_id`,
	`spsessions`.`time`,
	`spectors`.`email`,
	`spectors`.`last_visit`,
	`spectors`.`name`
	FROM
	`spsessions`
	INNER JOIN `spectors` ON `spsessions`.`spect_id` = `spectors`.`spect_id`
	WHERE `usersess` = '$usersess'
	");
	
	if(mysqli_num_rows($query) > 0)
	{
		foreach($query as $q)
		{
			$usermail = $q['email'];
			//var_dump($usermail);
			$spect_id = $q['spect_id'];
			if(is_null($q['name']) or $q['name'] == '')
			{				
				$namem = explode('@',$usermail);
				$name = $namem[0];
			}else
			$name = $q['name'];
		}
		if(($myip or $officeip) and in_array($usermail,['roman.chubich@gmail.com', 'ainaraen@gmail.com']))
			$admin = 1;
		
		$query = qwe("
		UPDATE `spsessions` 
		SET `last_ip` = '$ip' 
		WHERE `usersess` = '$usersess'
		");
	}else
	{
		$usersess = '';
		$spect_id = 0;
	}
}
	
$identy = Metka($ip);

?>