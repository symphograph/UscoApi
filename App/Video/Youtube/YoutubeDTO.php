<?php

namespace App\Video\Youtube;

use Symphograph\Bicycle\DTO\DTOTrait;

class YoutubeDTO {
    use DTOTrait;
    const string tableName = "video";

    public int    $id;
    public string $name;
    public string $youtubeId;
    public ?int    $announceId;
    public ?string $descr;
    public string $createdAt;
    public bool   $isShow;

}