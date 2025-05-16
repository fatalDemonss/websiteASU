<?php
require '../db.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: blacklist.php');
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("UPDATE users SET is_blacklisted = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: blacklist.php');
exit();
