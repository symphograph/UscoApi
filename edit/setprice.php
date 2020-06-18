<?php
include '../includs/ip.php';
include '../functions/functions.php';
include_once '../includs/check.php';
if(!$myip and !$officeip)
exit();
var_dump($_POST);
$ev_id = intval($_POST['ev_id']);
foreach($_POST['price'] as $k => $v)
{
	$group = intval($k);
	$price = intval($v);
	qwe("
	REPLACE INTO `rodina_price` 
	(`ev_id`, `group_id`, `price`)
	VALUES
	('$ev_id', '$group', '$price')
	");
}

?>
<meta http-equiv='refresh' content='0; url=../rodina.php?ev_id=<?php echo $ev_id?>'/>