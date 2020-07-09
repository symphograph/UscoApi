<?php
$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
?>
<div class="header">
    <div class="hederc">
        <nav class="bignav">

          <ul class="topmenu">
            <li><a href="<?php echo $host;?>index.php">Оркестр</a>
            <ul class="submenu">
            <li>
                <li><a href="<?php echo $host;?>tsa.php">Тигран Ахназарян</a></li>
                <li><a href="<?php echo $host;?>staff.php">Состав оркестра</a></li>
                <li><a href="<?php echo $host;?>history.php">История</a></li>
                <li><a href="<?php echo $host;?>zag.php">Александр Зражаев</a></li>
            </ul>
            </li>
            <li><a href="<?php echo $host;?>anonces.php">Афиша</a>
             <ul class="submenu">
                <li><a href="<?php echo $host;?>anonces.php">Предстоящие</a></li>
                <li><a href="<?php echo $host;?>complited.php">Прошедшие</a></li>
            </ul>
            </li>

            <li><a href="">Медиа</a>
            <ul class="submenu">
                <li><a href="<?php echo $host;?>gallery.php">Фото</a></li>
                <li><a href="<?php echo $host;?>video.php">Видео</a></li>
            </ul>
            </li>

            <li><a href="<?php echo $host;?>">Пресса</a>
            <ul class="submenu">
                <li><a href="<?php echo $host;?>news.php">Новости</a></li>
                <li><a href="<?php echo $host;?>articles.php">Статьи</a></li>
            </ul>
            </li>
            <li><a href="<?php echo $host;?>contacts.php">Контакты</a></li>
            <li><a href="<?php echo $host;?>documents.php">Документы</a></li>

          </ul>
        </nav>
    </div>
    <div class="topline">

        <div class="topin">

            <div>
            </div>
                <div class="sitename">
                    <div><a id="uscologo" href="<?php echo '/index.php';?>"><img src="<?php echo $host;?>img/logo/logo.svg?ver=2"  width="80px" height="80px" alt="Логотип"/></a>
                    <a id="orgname" style="text-decoration: none; color: #e7ddcb" href="<?php echo $host;?>index.php">Южно-Сахалинский камерный оркестр</a></div>
                </div>

            <div class="conductor">
                <span>Художественный руководитель</span>
                <span>и главный дирижер</span>
            <a href="<?php echo $host.'tsa.php'?>"><span class="tsa">Тигран Ахназарян</span></a>
            </div>
        </div>
    </div>
</div>