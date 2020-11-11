<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/../includs/check.php';
?>
<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8">
<?php
$p_title = 'Документы';
$ver = random_str(8);
?>
<title><?php echo $p_title;?></title>
<link rel="icon" href="img/logo/logo.svg" sizes="any" type="image/svg+xml">
<?php CssMeta(['menu.css','index.css','menum.css', 'documents.css', 'right_nav.css'])?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
<?php
require_once $root.'/../includs/links.php';
require_once $root.'/../includs/header.php';
?>


<div class="content">
    <div class="filesarea">
        <div class="p_title"><?php echo $p_title;?></div>
        <br>
        <details open="open"><summary>Устав учреждения</summary><br>
            <br>
            <p><a href="documents/ustav_2018_09_17_with_egrul.pdf" class="flink">Устав учреждения от 17.09.2018</a></p>
            <p><a href="documents/ustav_edit_25.01.2019_with_egrul.pdf" class="flink">Изменение в Устав от 08.02.2019 и 22.08.2019</a></p>
        </details>
        <br><hr><br>
        <details open="open"><summary>ФХД</summary><br>
            <p><a href="documents/Plan_FHD_2018.pdf" class="flink">План ФХД 2018</a></p>
            <p><a href="documents/Plan_FHD_2019.pdf" class="flink">План ФХД 2019</a></p>
            <p><a href="documents/Plan_FHD_2020.pdf" class="flink">План ФХД 2020</a></p>
            <p><a href="documents/Performance_reportFHD.pdf" class="flink">Отчет об исполнении ФХД</a></p>
            <p><a href="documents/Performance_reportFHD1.pdf" class="flink">Отчет об исполнении ФХД1</a></p>
            <p><a href="documents/Performance_reportFHD2.pdf" class="flink">Отчет об исполнении ФХД2</a></p>
        </details>
        <br><hr><br>
        <details open="open"><summary>Муниципальное задание</summary><br>

            <p><a href="documents/MUP_Task_2020.pdf" class="flink">Муниципальное задание 2020</a></p>
            <p><a href="documents/report_MTask_2018.doc" class="flink">Отчеты по выполнению Муниципального задания 2018</a></p>
            <p><a href="documents/report_MTask_2019.pdf" class="flink">Отчеты по выполнению Муниципального задания 2019</a></p>
            <p><a href="documents/report_MTask_2020.pdf" class="flink">Отчеты по выполнению Муниципального задания 2020</a></p>
        </details>
        <br><hr><br>
        <details open="open"><summary>Платные услуги</summary><br>
            <ol>
                <li>Организация и проведение концерта для детей детских садов, школьников, студентов.<br>
                    <span>Стоимость: Договорная</span><br></li>

                <li>Концерт с приглашением солиста из Сахалинского края<br>
                    <span>Стоимость: Договорная</span><br></li>
                <li>Концерт с приглашением солиста из другого федерального округа<br>
                    <span>Стоимость: Договорная</span><br></li>
                <li>Концерт без участия приглашенного солиста<br>
                    <span>Стоимость: Договорная</span><br></li>
                <li>Организация и (или) проведение выездного концерта<br>
                    <span>Стоимость: Договорная</span><br></li>
            </ol>
            <div class="tel"><a href="tel:+74242300518"/>+7-4242-300-518</a></div><br>
        </details>
        <br><hr><br>


        <details open="open"><summary>Материально-техническая база</summary><br>
        <div class="table">
            <div class="row">
                <div class="col"><b>Наименование</b></div>	<div class="col"><b>Показатели по учреждению</b></div>
            </div>
            <div class="row">
                <div class="col">Учреждение занимает отдельное здание, арендует (у кого), совместно с другими организациями (какими)</div>
                <div class="col">Арендует часть здания у ООО «Креветка»</div>
            </div>
            <div class="row">
                <div class="col">Система отопления здания</div>
                <div class="col">Централизованное теплоснабжение</div>
            </div>
            <div class="row">
                <div class="col">Наличие водопровода в здании (да \ нет)</div>
                <div class="col">Да</div>
            </div>
            <div class="row">
                <div class="col">Наличие канализации в здании (да \ нет)</div>
                <div class="col">Да</div>
            </div>
            <div class="row">
                <div class="col">Наличие средств противопожарной защиты</div>
                <div class="col">Имеется</div>
            </div>
            <div class="row">
                <div class="col">Наличие средств пожарного оповещения</div>
                <div class="col">Здания оборудованы автоматической пожарной сигнализацией, системой оповещения и управления людей</div>
            </div>
            <div class="row">
                <div class="col">Техническое состояние здания (удовлетворительное, требует кап. ремонта, аварийное, требует текущего ремонта, подлежит сносу)</div>
                <div class="col">Удовлетворительное</div>
            </div>
            <div class="row">
                <div class="col">Дополнительные оборудования здания</div>
                <div class="col">Пандус для инвалидов</div>
            </div>
            <div class="row">
                <div class="col">Сооружения на прилегающей территории здания</div>
                <div class="col">Парковочная зона</div>
            </div>
        </div>
        </details>
    </div>

</div>

<?php
require_once $root.'/../includs/footer.php';
?>
</body>
</html>