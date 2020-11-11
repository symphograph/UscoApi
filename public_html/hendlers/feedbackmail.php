<?php
if($_SERVER['REQUEST_URI'] != '/contacts_ttt.php')
    die();

if(empty($_POST))
    die();

$name = $_POST['name'];
$email = $_POST['email'];
$msg = $_POST['msg'];

