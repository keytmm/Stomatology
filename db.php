<?php
$servername = "localhost"; //хост
$database = "stomatology"; //имя базы данных
$user = "root"; //имя пользователя
$password = "root"; //пароль
$db = mysqli_connect($servername, $user, $password, $database);
if (!$db){
die("Connection failed: ".mysqli_connect_error());
}
?>