<?php
function dbconnect2()
{
    global $dbLink2, $myip, $connects2;

    $server_name = $_SERVER['SERVER_NAME'];

    foreach($connects2[$server_name] as $db)
    {
        extract($db);
    }


    if (!isset($dbLink2))
        $dbLink2 = mysqli_connect ($dbHost2, $dbUser2,$dbPass2,$dbName2)
        or die("<center><h1>Don't connect with database!!!</h1></center>");
}

// Функция выполнения запросов с логированием ошибок
function qwe2($sql)
{
    //var_dump($sql);
    global $dbLink2;

    if (!isset($dbLink2))
        dbconnect2();

    $return = mysqli_query($dbLink2,$sql);
    $error = mysqli_error($dbLink2);
    if (empty($error))
        return $return;


    $backtrace = debug_backtrace();
    $file = $backtrace[0]['file'];
    $file = explode($_SERVER['SERVER_NAME'],$file)[1];
    $file = $file.' (line '.$backtrace[0]['line'].')';
    writelog('sql_error', date("Y-m-d H:i:s")."\t".$error."\t".$file."\r\n".$sql);
    return false;

}
//dbconnect2();
?>
