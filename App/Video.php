<?php

namespace App;

use PDO;

class Video
{
    public function __construct(
        public string $youtubeId = '',
        public string $pw = '',
        public string $name = '',
        public        $vidId = null,
        public        $announceId = null,
        public        $descr = null,
        public        $createdAt = null,
        public        $isShow = null
    )
    {


    }

    public function __set(string $name, $value): void
    {
    }

    /**
     * @return array<self>
     */
    public static function getCollection(int $limit): array
    {
        $qwe = qwe("SELECT * FROM video where isShow = 1 ORDER BY createdAt DESC LIMIT :limit", ['limit' => $limit]);
        if (!$qwe->rowCount()) {
            return [];
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::class);
    }

    public static function getIFrame(string $youtubeId): string
    {
        return
            <<<HTML
            <div class="vitem" id="$youtubeId">
            
                <iframe 
                        src="https://www.youtube.com/embed/$youtubeId"
                        frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
        HTML;
    }


}