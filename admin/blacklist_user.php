<?php
require '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id'])) {
        header('Location: clients.php');
        exit();
    }
    $id = (int)$_GET['id'];
    ?>
    <!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
        <title>FatalDemon - Блокировка</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../styles.css">
    </head>
    <body>
        <div class="page-wrapper">
            <div class="content">
                <p class="welcome-title-admin2">Введите причину блокировки</p>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <label class="textAbout">Причина:<br>
                        <textarea class="comment-textarea2" name="reason" required></textarea>
                    </label><br><br>
                    <button class="block-button" type="submit">Заблокировать</button>
                    <a href="clients.php"><button class="edit-button" type="button">Отмена</button></a>
                </form>
            </div>
            <div class="image-bottom-left-small">
                <img src="../img/logo.png" alt="Логотип">
        </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// POST
$id = (int)$_POST['id'];
$reason = trim($_POST['reason']);
$date = date('Y-m-d H:i:s');

$stmt = $conn->prepare("UPDATE users SET is_blacklisted = 1, blacklist_reason = ?, blacklist_date = ? WHERE id = ?");
$stmt->bind_param("ssi", $reason, $date, $id);
$stmt->execute();

header('Location: clients.php');
exit();
