<?php

class FileHelper
{
    public static function FileList(string $dir): array
    {
        $files = scandir($dir);
        if(!$files)
            return [];
        $skip = ['.', '..'];
        $files2 = [];
        foreach($files as $file)
        {
            if(in_array($file, $skip) or is_dir($dir.'/'.$file))
                continue;
            $files2[] = $file;
        }
        return $files2;
    }

    public static function folderList(string $dir): array
    {
        //Получает массив с именами папок в директории
        $files = scandir($dir);
        $skip = ['.', '..'];
        $folders = [];
        foreach($files as $file)
        {
            if(!in_array($file, $skip) and is_dir($dir.'/'.$file))
                $folders[] = $file;
        }
        return($folders);
    }
}