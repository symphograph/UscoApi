<?php
$dbHost2='localhost';// чаще всего это так, но иногда требуется прописать ip адрес базы данных
$dbName2='graflastor_gko';// название вашей базы
$dbUser2='graflastor_gko';// пользователь базы данных
$dbPass2='xs1TN&Nq';// пароль пользователя
$link2 = mysqli_connect($dbHost2, $dbUser2, $dbPass2, $dbName2) 
    or die("<center><h1>Don't connect with database!!!</h1></center>" . mysqli_error());
?>
