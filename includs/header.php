<?php
$host = 'https://'.$_SERVER['SERVER_NAME'].'/';
?>
<div class="header">

    <div class="hederc">
        <nav class="bignav">
            <ul class="topmenu">
                <li>
                    <a href="<?php echo $host;?>index.php">ЮСКО</a>
                    <ul class="submenu">
                        <li><a href="<?php echo $host;?>tsa.php">Тигран Ахназарян</a></li>
                        <li><a href="<?php echo $host;?>staff.php">Состав оркестра</a></li>
                        <li><a href="<?php echo $host;?>zag.php">Александр Зражаев</a></li>
                        <li><a href="<?php echo $host;?>history.php">Историческая справка</a></li>
                    </ul>
                </li>

                <li><a href="<?php echo $host;?>vacancies.php">Вакансии</a></li>
                <li><a href="<?php echo $host;?>complited.php">Афиши</a></li>

                <li>
                    <a href="">Медиа</a>
                    <ul class="submenu">
                    <li><a href="<?php echo $host;?>gallery.php">Фото</a></li>
                    <li><a href="<?php echo $host;?>video.php">Видео</a></li>
                    </ul>
                </li>

                <li>
                    <a href="<?php echo $host;?>">Новости</a>
                    <ul class="submenu">
                    <li><a href="<?php echo $host;?>news.php">Новости оркестра</a></li>
                    <li><a href="<?php echo $host;?>articles.php">Статьи</a></li>
                    <li><a href="<?php echo $host;?>outside_news.php">Другие новости</a></li>
                    </ul>
                </li>

                <li><a href="<?php echo $host;?>contacts.php">Контакты</a></li>

                <li>
                    <a href="<?php echo $host;?>">Документы</a>
                    <ul class="submenu">
                        <li><a href="<?php echo $host;?>main.php">Основные сведения</a></li>
                        <li><a href="<?php echo $host;?>documents.php">Документы</a></li>
                        <li><a href="<?php echo $host;?>corruptcomp.php">Вместе против коррупции!</a></li>
                    </ul>
                </li>


            </ul>
        </nav>
    </div>
    <div class="topline">

        <div class="topin">


                <div class="sitename">
                    <div>
                        <a id="uscologo" href="<?php echo '/index.php';?>">
                            <img src="<?php echo $host;?>img/logo/logo.svg?ver=2"  alt="Логотип"/>
                        </a>
                        <a id="orgname" href="<?php echo $host;?>index.php">Южно-Сахалинский камерный оркестр</a>
                    </div>
                </div>

            <div class="conductor">
                <span>Художественный руководитель</span>
                <span>и главный дирижер</span>
                <a href="<?php echo $host.'tsa.php'?>"><span class="tsa">Тигран Ахназарян</span></a>
            </div>
            <div id="finevision_banner" title="Версия для слабовидящих"
                 onclick="finevision.activate_navbar()"
                 style="cursor: pointer;
                 ">

                <div class="conductor"><img width="30px" height="30px" src="img/loupe.svg?ver=3"><span>Версия для слабовидящих</span></div>
                <script src="https://finevision.ru/static/js/finevision_banner.js"></script>
            </div>


        </div>

    </div>
</div>
