<?php

use App\Test\TestClass;
use App\Test\TestClass2;

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

$start = microtime(true);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>test</title>
</head>
<body style="color: white; background-color: #262525">
<?php

function projectGallery(array $project_gallery)
{
    //Случаи прекращения работы функции желательно описывать в её начале.
    if (empty($project_gallery)) {
        return;
    }

    foreach ($project_gallery as $mediaItem) {
        if (mediaType($mediaItem) === 'video') {
            <<<HTML
                <video controls="" autoplay="" name="media" src="{$video['url']}" type="video/mp4" class="project-slider__image"></video>
            HTML;
        }

    }
}

function mediaType($mediaItem)
{
    //Определяем тип медиа, возвращаем результат.
    return 'video'; //или img
}

echo '<br>Время выполнения скрипта: ' . round(microtime(true) - $start, 4) . ' сек.';
?>
</body>