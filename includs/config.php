<?php


$server_name = $_SERVER['SERVER_NAME'];

function dbconnect() 
{
	global $dbLink, $myip, $connects;
	
	$server_name = $_SERVER['SERVER_NAME'];
	
	foreach($connects[$server_name] as $db)
	{
		extract($db);
	}
	

	if (!isset($dbLink)) 
		$dbLink = mysqli_connect ($dbHost, $dbUser,$dbPass,$dbName)
		or die("<center><h1>Don't connect with database!!!</h1></center>");
}

// Функция выполнения запросов с логированием ошибок
function qwe($sql)
{
	//var_dump($sql);
	global $dbLink;
	
	if (!isset($dbLink))
		dbconnect();
	
	$return = mysqli_query($dbLink,$sql);
	$error = mysqli_error($dbLink);
	if (empty($error))
		return $return;
	
	
	$backtrace = debug_backtrace();
	$file = $backtrace[0]['file'];
	$file = explode('public_html',$file)[1];
	$file = $file.' (line '.$backtrace[0]['line'].')';
	writelog('sql_error', date("Y-m-d H:i:s")."\t".$error."\t".$file."\r\n".$sql);
	return false;
	
}

// Процедура записи в лог фаил для записи ошибок
function writelog($typelog, $log_text) 
{
	$log = fopen($_SERVER['DOCUMENT_ROOT'].'/logs/'.$typelog.'.txt','a+');
	fwrite($log, "$log_text\r\n");
	fclose($log);
}
//
?>
