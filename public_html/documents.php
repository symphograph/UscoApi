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
		<p><a href="documents/ustav_2018_09_17_with_egrul.pdf" class="flink">Устав учреждения от 17.09.2018</a></p>
		<p><a href="documents/ustav_edit_25.01.2019_with_egrul.pdf" class="flink">Изменение в Устав от 08.02.2019 и 22.08.2019</a></p>
	</details><br><hr><br>
    <details open="open"><summary>Отчет об исполнении ФХД</summary><br>
        <p><a href="documents/Performance_reportFHD.pdf" class="flink">Отчет об исполнении ФХД</a></p>
        <p><a href="documents/Performance_reportFHD1.pdf" class="flink">Отчет об исполнении ФХД1</a></p>
        <p><a href="documents/Performance_reportFHD2.pdf" class="flink">Отчет об исполнении ФХД2</a></p>
    </details><br><hr><br>
<?php /*?>	
	<details open="open"><summary>Листы записи в ЕГРЮЛ</summary><br>
		<p><a href="documents/egrul_25.09.2018.pdf" class="flink">Лист записи в ЕГРЮЛ от 25.09.2018</a></p>
		<p><a href="documents/egrul_08.02.2019.pdf" class="flink">Лист записи в ЕГРЮЛ от 08.02.2019</a></p>
		<p><a href="documents/egrul_22.08.2019.pdf" class="flink">Лист записи в ЕГРЮЛ от 22.08.2019</a></p>
	</details><br><hr><br>
	<?php */?>	
<?php /*?>		<p><a href="ofdocs/inn.pdf" class="flink">Лицензия</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">Санитарно-эпидемиологическое заключение</a></p>
		<br><hr><br>
	<details><summary>Результаты проверок</summary>
		<div class="inspections">
			
				<div class="col1">№</div>
				<div class="col2">Дата и наименование проверки</div>
				<div class="col3">Акты проверок, предписания</div>
				<div class="col4">Исполнение предписаний</div>
				
			<div>1</div>
			<div>vcxzvcvcxz</div>
			<div>bvxvbx</div>
			<div>nbvcbnvcbnv xczcvxzvcvxzvcvxzvcvxcz</div>
			
			<div>2</div>
			<div>vcxzvcvcxz</div>
			<div>bvxvbx</div>
			<div>nbvcbnvcbnvxczcvxzv cvxzvcvxzvcvxcz</div>
			
			<div>3</div>
			<div>vcxzvcvcxz</div>
			<div>bvxvbx</div>
			<div>nbvcbnv cbnvxczcvxzvcvxzv cvxzvcvxcz</div>
			
			<div>4</div>
			<div>vcxzvcvcxz</div>
			<div>bvxvbx</div>
			<div>nbvcbnvcb nvxczcvxzvcv xzvcvxzvcvxcz</div>
			
		</div>
		<hr>
	</details><br><hr><br>
	<details><summary>Локальные акты</summary>
		<p><a href="ofdocs/inn.pdf" class="flink">gfsgf gfsdfg inn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gfsfgfsgfg  gfdsfginn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gfsdgfgf  gfdsfginn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gfsdfgfdsgfg  inn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">nbnvbnbvcnbn   inn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gsddfg gfdsfgss inn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gfsgf gfsdfg inn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gfsfgfsgfg  gfdsfginn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gfsdgfgf  gfdsfginn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gfsdfgfdsgfg  inn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">nbnvbnbvcnbn   inn.pdf</a></p>
		<p><a href="ofdocs/inn.pdf" class="flink">gsddfg gfdsfgss inn.pdf</a></p>
		<hr>
	</details><br><hr><br>
	<p><a href="ofdocs/inn.pdf" class="flink">Отчет о результатах самообследования</a></p>
	<p><a href="ofdocs/inn.pdf" class="flink">План финансово-хозяйственной деятельности</a></p>
<?php */?>

</div>

</div>

<?php
require_once $root.'/../includs/footer.php';
?>
</body>
</html>