<?php


class Video
{
    public function __construct(
        public string $youtube_id,
        public string $pw = '',
        public string $name = ''
    )
    {


    }

    public static function getVitem(string $youtube_id) : string
    {
        /*if(str_starts_with($_SERVER['SCRIPT_NAME'],'/api/')){
            return self::getForApi($youtube_id);
        }*/
        return self::getIFrame($youtube_id);
    }

    public static function getForApi(string $youtube_id) : string
    {
        return
            <<<HTML
                <div class="vitem">
                    <q-video
                    :ratio="16/9"
                    :src="https://www.youtube.com/embed/{$youtube_id}"
                    ></q-video>
                </div>
            HTML;
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