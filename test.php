<?php
include_once 'includs/ip.php';
include_once 'functions/functions.php';
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
$yd_url = 'https://cloud-api.yandex.net:443/v1/disk/public/resources?public_key=https://yadi.sk/d/1epqAPRtCaMHKQ';
$respons = file_get_contents($yd_url);
$respons = json_decode($respons,1);
$embedded = $respons['_embedded'];
$public_key = $embedded['public_key'];
$items = $embedded['items'];
//printr($embedded);
//exit();
foreach($items as $v)
{
	extract($v);
	if($type == 'dir')
	{
		$albums[] = [$v['name'],$path];
		$qbld = ['public_key'=>$public_key,'path'=>$path];
		$qbld = http_build_query($qbld);
		$url = 'https://cloud-api.yandex.net:443/v1/disk/public/resources?'.$qbld;
		$respons = file_get_contents($url);
		$respons = json_decode($respons,1);
		$items2 = $respons['_embedded']['items'];
		printr($items2);
	}else
		echo 'jjjj';
}
//printr($albums);
?>
</body>
</html>