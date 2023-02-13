<?php
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

use App\{Album, User};

$User = User::byCheck();
$_POST = json_decode(file_get_contents('php://input'), true)['params'];

if(empty($_POST['what']))
    die(http_response_code(400));

if($_POST['what'] == 'options') {
    $album = Album::getLast() or die(http_response_code(400));
    $opts = Album::getOptions();
    echo json_encode(['albumName' => $album, 'options' => $opts]);
    die();
}

if($_POST['what'] == 'data') {

    $album = $_POST['album'] ?? 0;
    if(!in_array($album, Album::getAlbums())) {
        die(http_response_code(400));
    }
    $files = Album::getImages($_SERVER['DOCUMENT_ROOT'] . '/img/albums/' . $album)
    or die(http_response_code(204));

    echo json_encode(['files' => $files]);
}
