<?php
include_once 'includs/ip.php';
include_once 'functions/functions.php';
include_once 'includs/config.php';
include_once 'includs/config2.php';
if(!$myip) exit;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Тест</title>
</head>

<body>
<?php
$qwe = qwe("SHOW DATABASES");
foreach ($qwe as $q)
{
    var_dump($q);
}
var_dump($qwe);
?>
</body>
</html>