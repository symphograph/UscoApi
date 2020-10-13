<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Список задач с drag & drop</title>
    <link href="css/drop.css?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/css/drop.css');?>" rel="stylesheet">

</head>
<body>
<div class="container">
    <section class="tasks">
        <h1 class="tasks__title">To do list</h1>

        <ul class="tasks__list" id="gr1">
            <li class="tasks__item" id="e1">learn HTML</li>
            <li class="tasks__item" id="e2">learn CSS</li>
            <li class="tasks__item" id="e3">learn JavaScript</li>
            <li class="tasks__item" id="e4">learn PHP</li>
            <li class="tasks__item" id="e5">stay alive</li>
        </ul>
        <ul class="tasks__list" id="gr2">
            <li class="tasks__item" id="e6">HTML</li>
            <li class="tasks__item" id="e7">CSS</li>
            <li class="tasks__item" id="e8">JavaScript</li>
            <li class="tasks__item" id="e9">PHP</li>
            <li class="tasks__item" id="e10">alive</li>
        </ul>
    </section>
</div>
</body>
<script type="text/javascript" src="js/drop.js?ver=<?php echo md5_file($_SERVER['DOCUMENT_ROOT'].'/js/drop.js');?>"></script>
</html>