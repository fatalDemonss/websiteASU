<?php
require '../db.php';
require '../auth.php';
$user = $_SESSION['user'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    // Получаем роль из формы, если нет или неправильная — по умолчанию client
    $role = isset($_POST['role']) && in_array($_POST['role'], ['client', 'admin']) ? $_POST['role'] : 'client';

    if (strlen($username) < 3 || strlen($password) < 3 || empty($full_name) || empty($email) || empty($phone)) {
        $error = "Заполните все поля корректно.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $error = "Логин уже занят.";
        } else {
            $hash = hash('sha256', $password);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, full_name, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $username, $hash, $role, $full_name, $email, $phone);
            if ($stmt->execute()) {
                $success = "Пользователь добавлен.";
            } else {
                $error = "Ошибка при добавлении.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FatalDemon - Добавить клиента</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <div class="page-wrapper">
        <div class="content">

            
            <!-- Меню -->
            <div class="center-container">
                <a class = "menu_btn" href="clients.php">Клиенты</a>
                <a class = "menu_btn_active" href="add_client.php">Добавить пользователя</a>
                <a class = "menu_btn" href="blacklist.php">Черный список</a>
                <a class = "menu_btn" href="../logout.php">Выйти</a>
            </div>


            <p class="welcome-title-admin2">Добавить пользователя</p>
            <?php if ($error): ?>
                <p style="color:red"><?= $error ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p style="color:green"><?= $success ?></p>
            <?php endif; ?>

            <form method="post">
                <input class="edit-input" type="text" name="full_name" placeholder="ФИО" required><br>
                <input class="edit-input" type="email" name="email" placeholder="Email" required><br>
                <input class="edit-input" type="text" name="phone" placeholder="Телефон" required><br>
                <input class="edit-input" type="text" name="username" placeholder="Логин" required><br>
                <input class="edit-input" type="password" name="password" placeholder="Пароль" required><br>

                <select class="edit-input" name="role" id="role">
                    <option value="client" selected>Клиент</option>
                    <option value="admin">Админ</option>
                </select><br><br>

                <button class="edit-button" type="submit">Добавить</button>
                <a href="clients.php"><button class="edit-button" type="button">Назад</button></a>
            </form>
        </div>
        <div class="image-bottom-left-small">
            <img src="../img/logo.png" alt="Логотип">
        </div>
    </div>
</body>
</html>
