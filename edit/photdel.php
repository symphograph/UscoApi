<?php
include_once '../includs/ip.php';
if(!$myip)
	exit();
foreach($_POST['photdelete'] as $d)
{
	unlink('../img/photo/big/'.$d);
	unlink('../img/photo/small/'.$d);
}
exit("<meta http-equiv='refresh' content='0; url=../photos.php'>");	
	
?>