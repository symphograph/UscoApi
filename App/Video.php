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
        public        $concert_id = null,
        public        $v_descript = null,
        public        $v_date = null,
        public        $show = null
    )
    {


    }

    public function __set(string $name, $value): void
    {
    }

    public static function getVitem(string $youtube_id) : string
    {
        if(
            str_starts_with($_SERVER['SCRIPT_NAME'],'/api/')
            ||
            str_starts_with($_SERVER['SCRIPT_NAME'],'/test')
            ){
            return PHP_EOL . "![video]($youtube_id)" . PHP_EOL;
        }
        return self::getIFrame($youtube_id);
    }

    public static function getCollection(int $limit): bool|string
    {
        $qwe = qwe( "SELECT * FROM video where `show` = 1 ORDER BY v_date DESC LIMIT :limit",['limit'=>$limit]);
        $qwe = $qwe->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,self::class);
        return json_encode(['data' => $qwe]);
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