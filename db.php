<?php
$host = 'localhost';
$db   = 'site_db';
$user = 'root';
$pass = ''; // Укажи свой пароль от MySQL, если есть

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
