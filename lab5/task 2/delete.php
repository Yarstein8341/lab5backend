<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lab5;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $delete_id = $_POST['delete_id'];

    $stmt = $pdo->prepare("DELETE FROM tov WHERE id = ?");
    $stmt->execute([$delete_id]);

    header('Location: index.php');
    exit();
}
?>
