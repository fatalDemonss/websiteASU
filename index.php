<?php
require 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $hash = hash('sha256', $password);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hash);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        // Проверка: если клиент и в черном списке — запрет входа
        if ($user['role'] === 'client' && $user['is_blacklisted'] == 1) {
            $error = "Вы в черном списке. Вход запрещен.";
        } else {
            $_SESSION['user'] = $user;

            header("Location: dashboard.php");
            exit();
        }
    } else {
        $error = "Неверный логин или пароль.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FatalDemon - Вход</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="page-wrapper">
    <div class="content">
      <p class="welcome-title">Добро пожаловать на сайт <b>FatalDemon</b></p>
      <p class="pleaseAuth">Пожалуйста, авторизуйтесь!</p>
      <?php if ($error): ?>
          <p style="color:red; text-align:center"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="post">
          <div class="center-container1">
              <input class="login-input" type="text" name="username" placeholder="Логин" required><br>
          </div>
          <div class="center-container1">
              <input class="login-input" type="password" name="password" placeholder="Пароль" required><br>
          </div>
          <div class="center-container1">
              <button class="login-button" type="submit">Войти</button>
          </div>
      </form>

      <div class="center-container1">
          <p><a href="register.php" class="register-link">Нет аккаунта? Зарегистрируйтесь!</a></p>
      </div>
    </div>

    <div class="image-bottom-left">
      <img src="../img/logo.png" alt="Логотип">
    </div>
  </div>
</body>
</html>
