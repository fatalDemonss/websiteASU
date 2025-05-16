<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<nav style="width:200px; float:left; border-right:1px solid #ccc; height:100vh;">
    <h3>Админ-панель</h3>
    <ul>
        <li><a href="clients.php">Клиенты</a></li>
        <li><a href="add_client.php">Добавить клиента</a></li>
        <li><a href="blacklist.php">Черный список</a></li>
        <li><a href="../logout.php">Выйти</a></li>
    </ul>
</nav>
<div style="margin-left:210px; padding:20px;">
