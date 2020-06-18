<?php
$secret = '9M10IFQ2S1nl5DlNDhr6L4r6';
//if(isset($_POST))
	//echo 'a14a3aa4';

if (!isset($_REQUEST)) { 
exit();
} 

//Строка для подтверждения адреса сервера из настроек Callback API 
$confirmation_token = 'a14a3aa4'; 

//Ключ доступа сообщества 
$token = 'f4e654a0f81816d8c6cb755ccc47a18f804d750e26ffc4df88f20b6a053fbbe80a623690c86b33a402f0e'; 

//Получаем и декодируем уведомление 
$data = json_decode(file_get_contents('php://input')); 

//Проверяем, что находится в поле "type" 
switch ($data->type) { 
//Если это уведомление для подтверждения адреса... 
case 'confirmation': 
//...отправляем строку для подтверждения 
echo $confirmation_token; 
break; 

//Если это уведомление о новом сообщении... 
case 'message_new': 
//...получаем id его автора 
$user_id = $data->object->user_id; 
//затем с помощью users.get получаем данные об авторе 
$user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids=".$user_id."&v=5.74")); 

//и извлекаем из ответа его имя 
$user_name = $user_info->response[0]->first_name; 

//С помощью messages.send отправляем ответное сообщение 
$request_params = array( 
'message' => "(Тестовый ответ) Здравствуйте, ".$user_name."! Мы получили Ваша сообщение. Скоро администратор Вам ответит. Если захочет.", 
'user_id' => $user_id, 
'access_token' => $token, 
'v' => '5.74' 
); 

$get_params = http_build_query($request_params); 

file_get_contents('https://api.vk.com/method/messages.send?'. $get_params); 

//Возвращаем "ok" серверу Callback API 

echo('ok'); 

break; 

case 'wall_post_new':
$text = $data->object->text;
		if(preg_match('Анонс',$text))
		{
include '../includs/config.php';
		qwe("INSERT INTO `anonces` 
		(`concert_id`, `description`)
		VALUES 
		('1', '$text')");
		}
	echo('ok'); 

break;	
}

?>