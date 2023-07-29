<?php
namespace App;

use Symphograph\Bicycle\Errors\ValidationErr;
use Symphograph\Bicycle\FileHelper;
class Album
{
    private const albumsDir = '/img/albums/';
    public string $name;
    public string $dir;
    public string $avatar;
    public string $date;
    public array  $images   = [];
    public array  $pwImages = [];

    public static function getImages(string $dir) : array
    {
        return FileHelper::FileList($dir);
    }

    /**
     * @return array<string>|bool
     */
    public static function getAlbums() : array|bool
    {
        $albums = FileHelper::folderList($_SERVER['DOCUMENT_ROOT'] . self::albumsDir);
        if(!count($albums)){
            return false;
        }

        return $albums;
    }

    public static function getLast() : string|bool
    {
        $albums = Album::getAlbums();
        if(!$albums)
            return false;
        return max($albums);
    }

    /**
     * @return array<self>
     */
    public static function getList() : array
    {
        $albums = Album::getAlbums();
        if(!$albums)
            return [];

        $list = [];
        foreach ($albums as $name){
            $list[] = self::byName($name);
        }
        return $list;
    }

    public static function byName(string $name, bool $safety = false): self
    {
        if($safety && !self::isNameExist($name)){
            throw new ValidationErr('invalid album name: ' . $name, 'Альбом не найден');
        }

        $Album = new self();
        $Album->name = $name;
        $Album->dir = self::albumsDir . $name;
        $Album->avatar = $Album->dir . '/pw/' . self::getImages($Album->dir)[0] ?? '';
        return $Album;
    }

    public static function isNameExist(string $name): bool
    {
        $albumNames = self::getAlbums();
        return in_array($name, $albumNames);
    }

    public function initImages(): void
    {
        $this->images = self::getImages($this->dir);
        $this->pwImages = self::getImages($this->dir . '/pw/');
    }

    private function initDate()
    {

    }

}