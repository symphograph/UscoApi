<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once $root.'/../includs/check.php';
if($admin)
{
	if(!empty($_POST['filename']))
	{
		//printr($_POST);
		foreach($_POST['filename'] as $dp)
		{
			unlink('ofdocs/' . $dp);
		}
	}
	
	if(!empty($_POST['MAX_FILE_SIZE']))
	{
	$uploaddir = 'ofdocs/';
	$uploadfile = $uploaddir . $_FILES['userfile']['name'];

	echo '<pre>';
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
	{
		echo "Файл корректен и был успешно загружен.\n";
	}else 
	{
		echo "Файл не прошел проверку!\n";
	}

	//echo 'Некоторая отладочная информация:';
	//print_r($_FILES);

	print "</pre>";
	}
}
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
<link href="css/menu.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/index.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/menum.css?ver=<?php echo $ver;?>" rel="stylesheet">
<link href="css/right_nav.css?ver=<?php echo $ver?>" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
</head>

<body>
<?php
require_once $root.'/../includs/links.php';
require_once $root.'/../includs/header.php';
?>


<div class="content">
<div class="filesarea">
<div class="p_title"><?php echo $p_title;?></div>
<?php
$files = scandir('ofdocs');
$files = array_diff($files, ['..', '.']);
if($admin)
	{
		?><form action="" method="post"><?php
	}
foreach($files as $f)
{
	$path_parts = pathinfo('ofdocs/'.$f);
	
	echo '<div class="files">';
	?><div class="ftiocon" style="background-image: url(img/ftypes/<?php echo $path_parts['extension'].'.png';?>);"><?php
	if($admin)
	{
		?><input type="checkbox" name="filename[]" value="<?php echo $f;?>"><?php
	}
		
	?></div><?php
	echo '<a href="ofdocs/'.$f.'" class="flink">'.$f.'</a></div>';
}
if($admin)
	{
		?><br><input type="submit" value="Удалить выбранное"/></form>
		<hr>
		
<form enctype="multipart/form-data" action="" method="POST">
<!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
<input type="hidden" name="MAX_FILE_SIZE" value="90000000" />
<!-- Название элемента input определяет имя в массиве $_FILES -->
<br><input name="userfile" type="file" /><br>
<p>Загрузить документ</p>
<br><input type="submit" value="Отправить файл" />
</form>
		<?php
	}
?>
</div>

</div>

<?php
require_once $root.'/../includs/footer.php';
?>
</body>
</html>