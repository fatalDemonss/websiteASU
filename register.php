<?php
require 'db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (strlen($username) < 3 || strlen($password) < 3 || empty($full_name) || empty($email) || empty($phone)) {
        $error = "Пожалуйста, заполните все поля корректно.";
    } else {
        $hash = hash('sha256', $password);

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $error = "Пользователь с таким логином уже существует.";
        } else {
            $role = 'client';
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, full_name, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $username, $hash, $role, $full_name, $email, $phone);
            if ($stmt->execute()) {
                $success = "Регистрация успешна! <a class='register-link' href='index.php'>Войти</a>";
            } else {
                $error = "Ошибка при регистрации.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FatalDemon - Регистрация</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-wrapper">
        <div class="content">
            <p class="register-title">Регистрация на <b>FatalDemon</b></p>
            <?php if ($error): ?>
                <p style="color:red"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p style="color:green"><?php echo $success; ?></p>
            <?php endif; ?>

            <form method="post">
                <div class="center-container">
                    <input class="login-input" type="text" name="full_name" placeholder="ФИО" required><br>
                </div>
                <div class="center-container">
                    <input class="login-input" type="email" name="email" placeholder="Email" required><br>
                </div>
                <div class="center-container">
                    <input class="login-input" type="text" name="phone" placeholder="Телефон" required><br>
                </div>
                <div class="center-container">
                    <input class="login-input" type="text" name="username" placeholder="Логин" required><br>
                </div>
                <div class="center-container">
                    <input class="login-input" type="password" name="password" placeholder="Пароль" required><br>
                </div>
                <div class="center-container">
                    <button class="register-button" type="submit">Зарегистрироваться</button>
                </div>
            </form>

            <p><a href="index.php" class="register-link">Есть аккаунт? Войти!</a></p>
        </div>
        <div class="image-bottom-left">
          <img src="../img/logo.png" alt="Логотип">
        </div>
    </div>
</body>
</html>
