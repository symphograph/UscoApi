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
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&display=swap" rel="stylesheet">
    <link href="css/index.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/index.css');?>" rel="stylesheet">
    <link href="css/menu.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/menu.css');?>" rel="stylesheet">
    <link href="css/afisha.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/afisha.css');?>" rel="stylesheet">
    <link href="css/staff.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/staff.css');?>" rel="stylesheet">
    <link href="css/right_nav.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/right_nav.css');?>" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
include 'includs/links.php';
include 'includs/header.php';

/*
if(!$myip)
{
    ?><div class="content">
    <div class="eventsarea">Технические работы</div><?php
    include 'includs/footer.php';
    ?></div><?php
    die();
}
*/
?>
<div class="topspase"></div>
    <div class="content">

        <div class="eventsarea">
            <div class="p_title">
                <?php echo $p_title;?>
            </div><br>
            <div class="gridarea">
<?php

$qwe = qwe2("
SELECT * FROM groups 
WHERE deep = 3 
ORDER BY priority
");
foreach ($qwe as $q)
{
    /**
     * @var int $group_id
     * @var string $group_name
     */
    extract($q);
    ?>
        <div class="group" id="gr_<?php echo $group_id; ?>">
        <span class="groupname"><?php echo $group_name; ?></span>
        <hr><br>
            <?php
            PlayersInGroup($group_id)
            ?>

        </div>
    <?php

}

function PlayersInGroup(int $group_id)
{
    global $link2;
    $qwe = qwe2("
    SELECT
	places.place_id, 
	personal.id as pers_id, 
	personal.`name`, 
	personal.patron, 
	personal.last_name, 
	places.chair, 
	pers_job.job_id, 
	jobs.job_name, 
	jobs.job_sname,
	COUNT(jobs.id) as job_cnt
FROM
	places
	INNER JOIN
	personal
	ON 
		personal.place_id = places.place_id AND
		places.group_id = '$group_id' AND
		personal.main < 3
	LEFT JOIN
	pers_job
	ON 
		personal.id = pers_job.pers_id
	LEFT JOIN
	jobs
	ON 
		pers_job.job_id = jobs.id AND jobs.site_visible = 1
		GROUP BY personal.id
ORDER BY
	places.place_id ASC
    ");
    foreach ($qwe as $q)
    {
        /**
         * @var int $pers_id
         * @var int $job_cnt
         */
        extract($q);
        ?>
        <div id="p_<?php echo $q['pers_id']?>">
            <span class="staffname"><?php echo $q['name'].' '.$q['last_name'];?></span>

            <?php
            if($job_cnt)
                PlayerTitles($pers_id);

            ?>
            <br><br>
        </div><?php
    }

}

function PlayerTitles(int $pers_id)
{
    global $link2;
    $qwe = qwe2("
    SELECT * FROM pers_job 
    INNER JOIN jobs ON pers_job.job_id = jobs.id 
    AND pers_job.pers_id = '$pers_id'
    AND jobs.site_visible = 1
    ");
    foreach ($qwe as $q)
    {
        /**
         * @var string $job_name
         */
        extract($q);
        ?><br><span class="staffjobname"><?php echo $job_name?></span><?php
    }

}

?>
        </div>
    </div>
</div>
<?php
include 'includs/footer.php';
?>
</body>
</html>