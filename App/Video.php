<?php
namespace App;

use PDO;

class Video
{
    public function __construct(
        public string $youtube_id = '',
        public string $pw = '',
        public string $name = '',
        public        $vid_id = null,
        public        $vid_name = null,
        public        $announceId = null,
        public        $v_descript = null,
        public        $v_date = null,
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
        $qwe = qwe( "SELECT * FROM video where isShow = 1 ORDER BY v_date DESC LIMIT :limit",['limit'=>$limit]);
        if(!$qwe->rowCount()){
            return [];
        }
        return $qwe->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,self::class);
    }

    public static function getIFrame(string $youtube_id) : string
    {
        return
            <<<HTML
            <div class="vitem" id="{$youtube_id}">
            
                <iframe 
                        src="https://www.youtube.com/embed/{$youtube_id}"
                        frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
            </div>
        HTML;
    }






}