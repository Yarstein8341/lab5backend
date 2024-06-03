<?php
session_start();
include 'config.php';

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != true) {
    header('Location: index.php');
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    session_unset();
    session_destroy();

    echo "Ваш профіль успішно видалено.";
    header('Location: index.php');
    exit();
} else {
    echo "Помилка видалення профілю: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
