<?php
require 'auth.php';
$user = $_SESSION['user'];

// Если это клиент — сразу отправляем в клиентский раздел
if ($user['role'] === 'client') {
    header("Location: client_panel.php"); // создайте client_panel.php, если нужно
    exit();
}

// Обработка нажатия кнопок формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mode'])) {
        if ($_POST['mode'] === 'admin' && $user['role'] === 'admin') {
            header("Location: admin/clients.php");  // Перенаправляем админа в админку
            exit();
        } elseif ($_POST['mode'] === 'client') {
            header("Location: client_panel.php");  // Перенаправляем клиента
            exit();
        } else {
            // Можно добавить обработку случая, если клиент пытается войти как админ
            echo "<p>У вас нет прав входа как админ.</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FatalDemon - Промежуточный вход</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-wrapper">
        <div class="content">
            <p class="welcome-title">Добро пожаловать, <b><?= htmlspecialchars($user['username']); ?>!</b></p>

            <form method="post">
                <button class="login-button-client" name="mode" value="admin">Войти как админ</button>
                <button class="login-button-client" name="mode" value="client">Войти как клиент</button>
            </form>

            <br><a class="exit-button-admin" href="index.php">Выйти</a>
        </div>
        <div class="image-bottom-left-small">
            <img src="../../img/logo.png" alt="Логотип">
        </div>
    </div>


    
</body>
</html>
