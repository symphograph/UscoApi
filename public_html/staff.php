<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once ROOT.'/includs/check.php';
require_once ROOT.'/includs/config2.php';

$p_title = 'Состав оркестра';
$ver = random_str(8);

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','afisha.css','staff.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once ROOT.'/includs/links.php';
require_once ROOT.'/includs/header.php';

/*
if(!$myip)
{
    ?><div class="content">
    <div class="eventsarea">Технические работы</div><?php
    require_once ROOT.'/includs/footer.php';
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
SELECT * FROM `groups` 
WHERE deep = 3 AND priority
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

function PlayersInGroup(int $group_id, $date = false)
{
    if(!$date)
        $date = date('Y-m-d');
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
		personal.place_id = places.place_id 
        AND places.group_id = '$group_id' 
        /*AND personal.main < 3 
        AND personal.main*/
	LEFT JOIN
	pers_job
	ON 
		personal.id = pers_job.pers_id 
        AND pers_job.job_id IN 
            (SELECT id FROM jobs WHERE site_visible)
	LEFT JOIN
	jobs
	ON 
		pers_job.job_id = jobs.id AND jobs.site_visible
    INNER JOIN reglist_ls rl 
        ON personal.id = rl.pers_id
        AND rl.ortid in (1,2)
        AND rl.main < 3
        AND '$date' BETWEEN rl.accept and rl.dismiss
    GROUP BY personal.id
ORDER BY
	site_visible desc, last_name, `name`
    ");
    foreach ($qwe as $q)
    {
        /**
         * @var int $pers_id
         * @var int $job_cnt
         */
        extract($q);
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/img/avatars/small/ava_'.$pers_id.'_min.png'))
        {
            $ava = 'img/avatars/small/ava_'.$pers_id.'_min.png';
            $ava = $ava.'?ver='.md5_file($_SERVER['DOCUMENT_ROOT'].'/'.$ava);
            $photo = 1;
        } else
        {
            $photo = 0;
            $ava = 'img/avatars/init_ava.png';
        }



        ?>

        <div class="pers_cell" id="p_<?php echo $pers_id?>" style="cursor: <?php if($photo) echo 'pointer'; else echo 'auto'?>">
            <div class="ava" style="background-image: url(<?php echo $ava?>)">
                <div class="ava_round"></div>
            </div>

            <div class="fio_col">
                <div class="fio_in">
                    <div class="staffname"><?php echo $q['name'].' '.$q['last_name'];?></div>

                    <?php
                    if($job_cnt)
                        PlayerTitles($pers_id);
                    ?>
                </div>
            </div>
        </div>
        <br><?php
    }

}

function PlayerTitles(int $pers_id)
{
    $qwe = qwe2("
    SELECT * FROM pers_job 
    INNER JOIN jobs ON pers_job.job_id = jobs.id 
    AND pers_job.pers_id = '$pers_id'
    AND jobs.site_visible
    ORDER BY site_visible
    ");
    foreach ($qwe as $q)
    {
        /**
         * @var string $job_name
         */
        extract($q);
        ?><div class="staffjobname"><?php echo $job_name?></div><?php
    }

}

?>
        </div>
    </div>
</div>
<?php
require_once ROOT.'/includs/footer.php';
?>
</body>
</html>