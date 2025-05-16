<?php
require '../db.php';
require '../auth.php';
$user = $_SESSION['user'];

$search = '';
$searchName = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';
$searchEmail = isset($_GET['search_email']) ? trim($_GET['search_email']) : '';

if ($searchName !== '' && $searchEmail !== '') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role='client' AND is_blacklisted=1 AND full_name LIKE ? AND email LIKE ? ORDER BY full_name ASC");
    if (!$stmt) die("Ошибка подготовки запроса: " . $conn->error);
    $likeName = "%$searchName%";
    $likeEmail = "%$searchEmail%";
    $stmt->bind_param("ss", $likeName, $likeEmail);
} elseif ($searchName !== '') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role='client' AND is_blacklisted=1 AND full_name LIKE ? ORDER BY full_name ASC");
    if (!$stmt) die("Ошибка подготовки запроса: " . $conn->error);
    $likeName = "%$searchName%";
    $stmt->bind_param("s", $likeName);
} elseif ($searchEmail !== '') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role='client' AND is_blacklisted=1 AND email LIKE ? ORDER BY full_name ASC");
    if (!$stmt) die("Ошибка подготовки запроса: " . $conn->error);
    $likeEmail = "%$searchEmail%";
    $stmt->bind_param("s", $likeEmail);
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role='client' AND is_blacklisted=1 ORDER BY full_name ASC");
    if (!$stmt) die("Ошибка подготовки запроса: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>Совпадений не найдено</p>";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>FatalDemon - Черный список</title>
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
                <a class = "menu_btn" href="add_client.php">Добавить пользователя</a>
                <a class = "menu_btn_active" href="blacklist.php">Черный список</a>
                <a class = "menu_btn" href="../logout.php">Выйти</a>
            </div>

            <p class="welcome-title-admin2">Черный список</p>
            

            <form method="get" style="margin-bottom: 20px;">
                <input class="found-input" type="text" name="search_name" placeholder="Поиск по ФИО" value="<?= htmlspecialchars($search) ?>">
                <input class="found-input" type="text" name="search_email" placeholder="Поиск по Email" value="<?= htmlspecialchars($_GET['search_email'] ?? '') ?>">
                <button type="submit" class="admin-button">Применить</button>
                <a class="null-button-admin" href="clients.php">Сброс</a>
            </form>

            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ФИО</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Логин</th>
                        <th>Причина блокировки</th>
                        <th>Дата блокировки</th>
                        <th>Комментарий</th>
                        <th>Действие</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input class="table-input" type="text" readonly value="<?= htmlspecialchars($row['full_name']) ?>"></td>
                        <td><input class="table-input" type="text" readonly value="<?= htmlspecialchars($row['email']) ?>"></td>
                        <td><input class="table-input" type="text" readonly value="<?= htmlspecialchars($row['phone']) ?>"></td>
                        <td><input class="table-input" type="text" readonly value="<?= htmlspecialchars($row['username']) ?>"></td>
                        <td><input class="table-input" type="text" readonly value="<?= htmlspecialchars($row['blacklist_reason']) ?>"></td>
                        <td><input class="table-input" type="text" readonly value="<?= htmlspecialchars($row['blacklist_date']) ?>"></td>
                        <td><input class="table-input" type="text" readonly value="<?= htmlspecialchars($row['comment']) ?>"></td>
                        <td class="actions">
                            <a href="unblacklist_user.php?id=<?= $row['id'] ?>" class="icon-button edit">
                              <img src="../img/back.png" alt="Редактировать" width="20" height="20">
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="image-bottom-left-small">
            <img src="../img/logo.png" alt="Логотип">
        </div>
    </div>
</body>
