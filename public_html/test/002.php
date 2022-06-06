<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
    <?php CssMeta(['menu.css', 'index.css', 'news.css', 'menum.css', 'right_nav.css']) ?>
</head>

<body>
<?php
printr(empty($_COOKIE['identy']));
//printr($_SERVER);
?>
</body>
</html>