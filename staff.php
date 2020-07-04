<?php
include 'includs/ip.php';
include 'functions/functions.php';
include_once 'includs/check.php';
include_once 'includs/config2.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    $p_title = 'Состав оркестра';
    $ver = random_str(8);
    ?>
    <title><?php echo $p_title;?></title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&display=swap" rel="stylesheet">
    <link href="css/index.css?ver=<?php echo md5_file('css/index.css');?>" rel="stylesheet">
    <link href="css/menu.css?ver=<?php echo md5_file('css/menu.css');?>" rel="stylesheet">
    <link href="css/staff.css?ver=<?php echo md5_file('css/staff.css');?>" rel="stylesheet">
    <link href="css/right_nav.css?ver=<?php echo md5_file('right_nav.css');?>" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
include 'includs/links.php';
include 'includs/header.php';
//include 'includs/config.php';
	
?>
<div class="topspase"></div>
<div class="content">
<div class="p_title">
<?php echo $p_title;?>
</div>
<div class="groups">
<?php
$query = mysqli_query($link2,"
SELECT
places.place_id,
places.group_id,
places.pers_id,
personal.last_name,
personal.`name`,
personal.patron,
groups.group_name,
reglist_ls.job_id,
jobs.job_sname,
reglist_ls.ortid,
reglist_ls.main
FROM
places
INNER JOIN groups ON places.group_id = groups.group_id
INNER JOIN personal ON places.pers_id = personal.id
INNER JOIN reglist_ls ON places.pers_id = reglist_ls.pers_id AND NOW() BETWEEN reglist_ls.accept AND reglist_ls.dismiss
AND reglist_ls.job_id in (7,8,9)
LEFT JOIN jobs ON reglist_ls.job_id = jobs.id

ORDER BY
groups.priority ASC,
places.place_id ASC
	");
$group_id = 0;
$next = 0;
$i = 0;
foreach($query as $q)
{
	if($q['group_id'] != $next)
	{
	if($i>0) echo '</div>';
	echo '<div class="group">';
	echo '<span class="groupname">'.$q['group_name'].'</span><hr><br>';
	}
	?>
	<span class="staffname"><?php echo $q['name'].' '.$q['last_name'];?></span>
	
	<?php
	//if($q['job_id']>0 and $q['job_id'] != 2)
	?><br><span class="staffjobname"><?php echo $q['job_sname']?></span><?php
	if($q['main']>2)
		echo ' (приглашенный артист)';
	$next = $q['group_id'];
	$i++;
	echo '<br><br>';
}
//echo $i;
?>
</div>
</div>
<?php
include 'includs/footer.php';
?>
</body>
</html>