<?php
require 'auth.php';
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FatalDemon - Главная страница</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="page-wrapper">
    <div class="content">
      <p class="welcome-title">Добро пожаловать, <b><?= htmlspecialchars($user['username']); ?>!</b></p>
      <p class="textAbout1">Данный сайт разработан в <b><i>учебных</i></b> целях для демонстрации базовых навыков веб-разработки. Он включает в себя систему регистрации и авторизации пользователей с разграничением ролей (администратор и клиент), панель администратора для управления списком клиентов, а также возможность добавления пользователей в черный список. Проект подключен к базе данных через <b><i>XAMPP</i></b> и реализует основные функции, необходимые для администрирования пользовательской базы.</p>



      <br><a class="exit-button" href="index.php">Выйти</a>
    </div>
    <div class="image-bottom-left">
      <img src="../img/logo.png" alt="Логотип">
    </div>
  </div>
</body>
</html>
