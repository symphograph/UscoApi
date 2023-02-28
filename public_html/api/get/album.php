<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Album, User};
use Symphograph\Bicycle\Api\Response;
use Symphograph\Bicycle\Errors\AppErr;
use Symphograph\Bicycle\Errors\ValidationErr;

$User = User::byCheck();

if(empty($_POST['what'])){
    throw new ValidationErr('what');
}

if($_POST['what'] == 'options') {
    $album = Album::getLast() or
        throw new AppErr('Album::getLast() err', 'Не найден последний альбом');

    $opts = Album::getOptions() or
        throw new AppErr('Album::getOptions() err', 'Список не найден');

    Response::data(['albumName' => $album, 'options' => $opts]);
}

if($_POST['what'] == 'data') {

    $album = $_POST['album'] ?? 0;
    if(!in_array($album, Album::getAlbums())) {
        throw new AppErr('Album err', 'Альбом не найден');
    }
    $files = Album::getImages($_SERVER['DOCUMENT_ROOT'] . '/img/albums/' . $album) or
        throw new AppErr('getImages err', 'Изображения не найдены');

    Response::data(['files' => $files]);
}
