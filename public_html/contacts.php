<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/check.php';

$p_title = 'Контакты';
$ver = random_str(8);
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $p_title;?></title>
    <link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
    <?php CssMeta(['menu.css','index.css','menum.css', 'right_nav.css'])?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="token" content="<?php echo SetToken($identy)?>">
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/links.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/header.php';
?>


<div class="content">
    <div class="text">
        <div class="p_title"><?php echo $p_title;?></div><br>
        <p>г. Южно-Сахалинск, ул.Ленина, д. 156</p><br>
        Директор<br>
        <b>Кириллова Майя Владимировна</b>
        <div class="tel"><a href="tel:+74242300518">+7-4242-300-518</a></div><br>
        <div class="tel"><a href="tel:+79632892316">+7-963-289-23-16</a></div>
        <br><br>
        Художественный руководитель и главный дирижер<br>
        <b>Ахназарян Тигран Суренович</b>
        <div class="tel"><a href="tel:+74242300518">+7-4242-300-518</a></div>
        <br><br>
        Старший администратор
        <div class="tel"><a href="tel:+74242300518">+7-4242-300-518</a></div>
        <br><br>
        Специалист по кадрам
        <div class="tel"><a href="tel:+74242300518">+7-4242-300-518</a></div><br>
        <br>
        <b>График работы</b>
        <ul>
            <li><div>Понедельник - Пятница</div><b>9:00 - 17:15</b></li><br>
            <li><div>Суббота - Воскресенье</div>Выходные дни</li>
        </ul>
        <br><br>
        Emails:<br>
        <a href="mailto:mbu-gko@yandex.ru">mbu-gko@yandex.ru</a><br>
        <a href="mailto:yusgo.mbuyusko@sakhalin.gov.ru">yusgo.mbuyusko@sakhalin.gov.ru</a><br>
        <br><br>


        <br>
        <div class="map">
        <iframe src="https://yandex.ru/map-widget/v1/-/ZU0EaABiTkMFXEJuZWJ4d3phYQA=/?ll=142.727859%2C46.966131&z=19" width="100%" height="400" frameborder="1" allowfullscreen="true"></iframe>
        </div>
        <br><br>

        <div class="fidback_area">
            <input id="name" type="text" required placeholder="Ваше имя (ФИО)">
            <?php //Запутаем бота, сами не путаемся. ?>
            <input id="unused" type="email" required placeholder="Ваш email">
            <input id="email" type="email" required placeholder="Ваш email">
            <textarea id="msg" placeholder="Напишите что-нибудь"></textarea>
            <button id="msend" class="bybtn"><span class="bybtntxt">Отправить</span></button>
            <br><br>
            <span>*Нажимая "отправить", Вы соглашаетесь с <a href="pdn.php">правилами</a> обработки персональных данных.</span>
        </div>
    </div>
</div>
<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/footer.php';
?>
<script src="js/mail.js?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/js/mail.js')?>"></script>
</body>
</html>