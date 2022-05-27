<?php

class PathHelper
{
    const rouresFile = '/includs/routes.txt';

    private static function linksFromFile(): array
    {
        $rFile = dirname($_SERVER['DOCUMENT_ROOT']) . self::rouresFile;
        $routes = file_get_contents($rFile);
        $routes = explode(PHP_EOL,$routes);
        $arr = [];
        foreach ($routes as $r){
            if(str_starts_with(trim($r),'path: ')){
                $path = str_replace(['path: ', "'", ','], '', $r);
                $path = trim($path);
                if(str_starts_with($path,'/:catchAll')){
                    //echo $path;
                    continue;
                }

                $arr[] = $path;
            }
        }
        //$arr[] = '';
        return $arr;
    }

    public static function whiteList(){
        $links = self::linksFromFile();
        $arr = [];
        foreach ($links as $l){
            $l = explode('/',$l);

            $arr[] = '/' . $l[1];
        }
        return $arr;
    }

    public static function chkVar(string $path) {
        $arr = explode('/',$path);
        $arr = array_map('trim',$arr);
        $arr = array_filter($arr);
        return $arr[2] ?? false;
    }

    public static function alowedVars()
    {
        $links = self::linksFromFile();
        $arr = [];
        foreach ($links as $l){
            $var = chkVar($l);
            if($var)
                $arr[] = $var;
        }
        return $arr;
    }

    public static function validPath(string $path): string
    {
        $path = trim($path);
        $white = self::whiteList();
        //$alowedVars = self::alowedVars();
        if (in_array($path, $white))
            return $path;

        $var = intval(self::chkVar($path));
        if($var){
            $arr = explode('/',$path);
            if(in_array('/' . $arr[1],$white))
            return '/' . $arr[1] . '/' . $arr[2];
        }

        return '/';
    }
}