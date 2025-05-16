<?php
require '../db.php';
//include 'header.php';
require '../auth.php';
$user = $_SESSION['user'];

$nameFilter = isset($_GET['name']) ? trim($_GET['name']) : '';
$emailFilter = isset($_GET['email']) ? trim($_GET['email']) : '';
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'asc'; // по умолчанию А→Я

// Безопасная проверка сортировки
$sortOrder = strtolower($sortOrder) === 'desc' ? 'DESC' : 'ASC';

$sql = "SELECT * FROM users WHERE role = 'client' AND is_blacklisted = 0";
$params = [];

if ($nameFilter !== '') {
    $sql .= " AND full_name LIKE ?";
    $params[] = "%" . $nameFilter . "%";
}
if ($emailFilter !== '') {
    $sql .= " AND email LIKE ?";
    $params[] = "%" . $emailFilter . "%";
}

$sql .= " ORDER BY full_name $sortOrder";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
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
        <p class="welcome-title-admin2">Добро пожаловать в Админ-Панель, <b><?= htmlspecialchars($user['username']); ?>!</b></p>

        <!-- Меню -->
        <div class="center-container">
            <a class = "menu_btn_active" href="clients.php">Клиенты</a>
            <a class = "menu_btn" href="add_client.php">Добавить пользователя</a>
            <a class = "menu_btn" href="blacklist.php">Черный список</a>
            <a class = "menu_btn" href="../logout.php">Выйти</a>
        </div>


        <form method="get" style="margin-bottom: 20px;">
            <input class="found-input" type="text" name="name" placeholder="Поиск по ФИО" value="<?= htmlspecialchars($nameFilter) ?>">
            <input class="found-input" type="text" name="email" placeholder="Поиск по Email" value="<?= htmlspecialchars($emailFilter) ?>">

            <label for="sort" class="sort_text">Сортировать по ФИО:</label>
            <select name="sort" id="sort">
                <option value="asc" <?= $sortOrder === 'ASC' ? 'selected' : '' ?>>А → Я</option>
                <option value="desc" <?= $sortOrder === 'DESC' ? 'selected' : '' ?>>Я → А</option>
            </select>

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
                    <td><input class="table-input" type="text" readonly value="<?= htmlspecialchars($row['comment']) ?>"></td>
                    <td class="actions">
                        <a href="edit_user.php?id=<?= $row['id'] ?>" class="icon-button edit">
                          <img src="../img/edit.png" alt="Редактировать" width="20" height="20">
                        </a>
                        <a href="blacklist_user.php?id=<?= $row['id'] ?>" class="icon-button block">
                          <img src="../img/block.png" alt="В черный список" width="20" height="20">
                        </a>
                        <a href="delete_user.php?id=<?= $row['id'] ?>" class="icon-button delete">
                          <img src="../img/delete.png" alt="Удалить" width="20" height="20">
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

