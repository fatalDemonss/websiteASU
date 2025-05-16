<?php
require '../db.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: clients.php');
    exit();
}

$id = (int)$_GET['id'];

// Обработка сохранения
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, phone=?, username=?, comment=? WHERE id=?");
    $stmt->bind_param("sssssi", $full_name, $email, $phone, $username, $comment, $id);
    $stmt->execute();

    header('Location: clients.php');
    exit();
}

// Получаем данные пользователя
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FatalDemon - Вход</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <div class="page-wrapper">
        <div class="content">
            <p class="welcome-title-admin2">Редактирование клиента</p>

            <form method="post">
                <label class="textAbout">ФИО:<br>
                    <input class="edit-input" type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                </label><br><br>

                <label class="textAbout">Email:<br>
                    <input class="edit-input" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </label><br><br>

                <label class="textAbout">Телефон:<br>
                    <input class="edit-input" type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
                </label><br><br>

                <label class="textAbout">Логин:<br>
                    <input class="edit-input" type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </label><br><br>

                <label class="textAbout">Комментарий:<br>
                    <textarea class="comment-textarea" name="comment"><?= htmlspecialchars($user['comment']) ?></textarea>
                </label><br><br>

                <button class="edit-button" type="submit">Сохранить</button>
                <a href="clients.php"><button class="edit-button" type="button">Отмена</button></a>
            </form>
        </div>
        <div class="image-bottom-left-small">
            <img src="../img/logo.png" alt="Логотип">
        </div>
    </div>
</body>
