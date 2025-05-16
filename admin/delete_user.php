<?php
require '../db.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: clients.php');
    exit();
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header('Location: clients.php');
exit();
