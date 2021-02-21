<?php
function ReplFoldersFromYaDisk($albumsdir,$shared_folder_link)
{
	$yd_url = 'https://cloud-api.yandex.net:443/v1/disk/public/resources?public_key='.$shared_folder_link;
	$respons = file_get_contents($yd_url);
	if(!$respons) return false;
	
	$respons = json_decode($respons,1);
	$embedded = $respons['_embedded'];
	$public_key = $embedded['public_key'];
	$items = $embedded['items'];
	$folders = [];
	foreach($items as $k => $v)
	{
		
		extract($v);
		
		if($type != 'dir') continue;//Файлы в корневой папке не нужны.
		if (!file_exists($albumsdir.$name))
		{
			//Кладём папку альбома на сервер, если её нет
			mkdir($albumsdir.$name);
		}
		
		$folders[] = $name;//Папки на YaDisk

		$path = '/'.$name;
		$qbld = ['public_key'=>$public_key,'path'=>$path,'limit'=>100];
		$qbld = http_build_query($qbld);
		$url = 'https://cloud-api.yandex.net:443/v1/disk/public/resources?'.$qbld;
		$respons = file_get_contents($url);
		if(!$respons) continue;
		$respons = json_decode($respons,1);
		$embedded = $respons['_embedded'];
		$items2 = $embedded['items'];
		ReplFilesFromYaDisk($items2,$albumsdir.$name);

		//break;
	}
	
	$myfolders = FolderList($albumsdir);
	
	foreach($myfolders as $k=>$folder)//Удаляем папки, которых нет на YaDisk
	{
		if(!in_array($folder,$folders))
		{
			delFolderRecurs($albumsdir.$folder);
		}
	}
}

function ReplFilesFromYaDisk($items2,$folder)
{
	$files = [];
	foreach($items2 as $k => $v)
	{
		extract($v);
		//printr($v);
		
		$files[] = $name;//Файлы на YaDisk
		if(file_exists("$folder/$name"))
		{
			//echo 'Такой уже есть<br>';
			$mymd5 = md5_file("$folder/$name");
			$NeedLoad = ($mymd5 != $md5);
		}else
		{
			$NeedLoad = true;
			//echo 'Новый файл<br>';
		}
		if($NeedLoad)
		{
			$pw = file_get_contents($preview);
			file_force_contents($folder.'/pw/'.$name, $pw);
			$fileimg = file_get_contents($file);
			file_force_contents("$folder/$name", $fileimg);	
		}
	}
	//printr($files);
	$myfiles = FileList($folder);
	$diff = array_diff($myfiles, $files);
	//Удаляем фалы, которых нет на YaDisk
	if(count($diff)>0)
	foreach($diff as $key =>$del)
	{
		unlink("$folder/$del");
	}
	//printr($diff);
}

function Albums($albumsdir)
{
	$myfolders = FolderList($albumsdir);

	arsort($myfolders);

	foreach($myfolders as $k => $name)
	{	
		
		$folder = $albumsdir.$name;
		$items2 = FileList($folder);
		?>
		{
			title: '<?php echo $name?>',
			items: [
				<?php
				Items($items2,$folder);
				?>
			]
		},

		<?php	
	}
	
}

function Items($items2,$folder)
{
	foreach($items2 as $k => $name)
	{
		$folder_conv = str_replace([' ','(',')'],['%20','&#040;','&#041;'],$folder);
		$name_conv = str_replace([' '],['%20'],$name);
		//$folder_conv =  myUrlEncode($folder);
		//$name_conv =  myUrlEncode($name);
		?>
		{
		url: '<?php echo "$folder_conv/$name_conv"?>',
		thumbUrl: '<?php echo "$folder_conv/pw/$name_conv"?>',
		title: '<?php echo $name?>' ,
		hash: '<?php echo md5_file("$folder/$name");?>'
		},

		<?php
	}
	
}
?>