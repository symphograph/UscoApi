<?php
namespace App;
class Album
{

    public static function getImages(string $dir) : array
    {
        return FileHelper::FileList($dir);
    }

    public static function getAlbums() : array|bool
    {
        $albums = FileHelper::folderList($_SERVER['DOCUMENT_ROOT'] . '/img/albums');
        if(!count($albums))
            return false;

        return $albums;
    }

    public static function getLast() : string|bool
    {
        $albums = Album::getAlbums();
        if(!$albums)
            return false;
        return max($albums);
    }

    public static function getOptions() : array|bool
    {
        $albums = Album::getAlbums();
        if(!$albums)
            return false;

        $opts = [];
        foreach ($albums as $al){
            $opts[] = [
                'value' => '/img/albums/' . $al,
                'label' => $al,
            ];
        }
        return $opts;
    }
}