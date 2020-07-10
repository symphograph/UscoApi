<?php
if(!isset($ip)) exit();
$job_id = 1;
$lvl = 100;
function top_links($lvl,$job_id)
{
    $host = 'https://'.$_SERVER['HTTP_HOST'].'/';
	?>
	<input type="checkbox" id="nav-toggle" autocomplete="off" hidden>
	<nav class="nav no-print">
        <label for="nav-toggle" class="nav-toggle" onclick></label>
        <h2 class="glav">
            <a href="index.php">Главная</a>
        </h2>
        <ul>
            <li><a href="<?php echo $host;?>tsa.php"> Тигран Ахназарян</a></li>
            <li><details><summary>Оркестр</summary>
                <ul>
                    <li><a href="<?php echo $host;?>tsa.php"> Тигран Ахназарян</a></li>
                    <li><a href="<?php echo $host;?>staff.php"> Состав оркестра</a></li>
                    <li><a href="<?php echo $host;?>main.php"> Основные сведения</a></li>
                    <li><a href="<?php echo $host;?>history.php"> История</a></li>
                    <li><a href="<?php echo $host;?>zag.php"> Александр Зражаев</a></li>
                </ul>
            </details>
            </li>
            <li><details><summary>Афиша</summary>
                <ul>
                    <li><a href="<?php echo $host;?>anonces.php">Предстоящие</a></li>
                    <li><a href="<?php echo $host;?>complited.php">Прошедшие</a></li>
                </ul>
            </details>
            </li>
            <li><details><summary>Медиа</summary>
                <ul>
                    <li><a href="<?php echo $host;?>gallery.php"> Фото</a></li>
                    <li><a href="<?php echo $host;?>video.php"> Видео</a></li>
                </ul>
            </li>
            <li><details><summary>Пресса</summary>
                <ul>
                <li><a href="<?php echo $host;?>news.php">Новости</a></li>
                <li><a href="<?php echo $host;?>articles.php">Статьи</a></li>
                </ul>
            </details>
            </li>
            <li><a href="<?php echo $host;?>contacts.php"> Контакты</a></li>
            <li><a href="<?php echo $host;?>documents.php"> Документы</a></li>
        </ul>
    </nav>
<div class="mask-content"></div>
<?php
}
//echo 'lvl='.$lvl;
top_links($lvl,$job_id);
	?>