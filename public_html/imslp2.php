<?php 
require_once 'includs/ip.php';
require_once 'includs/config.php';
require_once 'functions/functions.php';
if(!$myip) die();

$qwe = qwe("
SELECT * FROM composers
WHERE composer_id > (SELECT max(composer_id) FROM works)
");
foreach($qwe as $q)
{
	extract($q);
	qwe("
	UPDATE works 
	SET composer_id = '$composer_id' 
	WHERE composer_id is NULL 
	AND composer = '$composer'"
   );
}