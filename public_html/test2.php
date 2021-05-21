<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/ip.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/functions/functions.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config.php';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/includs/config2.php';
if(!$myip) exit;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Тест</title>
    <?php CssMeta(['menu.css','index.css','news.css','menum.css', 'right_nav.css'])?>
</head>

<body>
<!-- Include stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>

<?php
printr(time() > (strtotime('2021-05-21 15:00:00')-3600*8));
?>



</body>
</html>