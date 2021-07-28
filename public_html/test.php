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
<!-- Create the editor container -->
<div id="toolbar"></div>
<div id="editor">

</div>

<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<!-- Initialize Quill editor -->
<script>
    var toolbarOptions = ['bold', 'image'];
    var quill = new Quill('#editor', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: "snow"
    });

</script>
<div class="content">
<?php
$Item = new NewsItem;
$Item->byId(44);
?>
<br><br>
Как будет в ленте
<div>
<?php
$Item->PrintItem();

?>
</div>
<hr><br><br>
Как будет на странице с новостью
<?php
$Item->PajeItem();
?>
</div>
</body>
</html>