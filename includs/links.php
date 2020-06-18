<?php
if(!isset($ip)) exit();
$job_id = 1;
$lvl = 100;
function top_links($lvl,$job_id)
{
	$href = '<a href="';
	if(!empty($_SERVER['PHP_SELF']))
	{
	$url = $_SERVER['PHP_SELF'];
	$path = parse_url($url, PHP_URL_PATH);
	if(strlen($path)>1)
		$href = '<a href="../';
	}
	/*
	$links = array(	
	'personal.php">Сотрудники</a>' => 10,	
	'repertuar.php">Репертуар</a>' => 30,
	'events.php">Ближайшее расписание</a>' => 10,
	'otmechalka.php">Отмечалка</a>' => 70,
	'tabels/timesheet.php">Табель</a>' => 80,
	);
	*/
	?>
	<input type="checkbox" id="nav-toggle" autocomplete="off" hidden>
	<nav class="nav no-print">
	<label for="nav-toggle" class="nav-toggle" onclick></label>
	<h2 class="glav"> 
		<a href="index.php">Главная</a>
	</h2>
	<ul>
	<li><?php echo $href;?>tsa.php"> Тигран Ахназарян</a></li>
	<li><details><summary>Оркестр</summary>
		<ul>
		<li><?php echo $href;?>tsa.php"> Тигран Ахназарян</a></li>
		<li><?php echo $href;?>staff.php"> Состав оркестра</a></li>
		<li><?php echo $href;?>history.php"> История</a></li>
		<li><?php echo $href;?>zag.php"> Александр Зражаев</a></li>
		</ul>
	</details>
	</li>
	<li><details><summary>Афиша</summary>
		<ul>
		<li><?php echo $href;?>anonces.php">Предстоящие</a></li>
		<li><?php echo $href;?>complited.php">Прошедшие</a></li>
		</ul>
	</details>
	</li>
	<li><details><summary>Медиа</summary>
	<ul>
	<li><?php echo $href;?>gallery.php"> Фото</a></li>
	<li><?php echo $href;?>video.php"> Видео</a></li>
	
	</ul>
	</li>
	<li><details><summary>Пресса</summary>
		<ul>
		<li><?php echo $href;?>news.php">Новости</a></li>
		<li><?php echo $href;?>articles.php">Статьи</a></li>
		</ul>
	</details>
	</li>
	<li><?php echo $href;?>contacts.php"> Контакты</a></li>
	<li><?php echo $href;?>documents.php"> Документы</a></li>
	</ul></nav>
<div class="mask-content"></div>
<?php
}
//echo 'lvl='.$lvl;
top_links($lvl,$job_id);
	?>