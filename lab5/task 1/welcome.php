<?php
session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != true) {
    header('Location: index.php');
    exit();
}

$welcome_message = "Ласкаво просимо, " . $_SESSION['username'] . "!";

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Головна сторінка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Головна сторінка</h1>
        <p><?php echo $welcome_message; ?></p>
        <p><a href="edit_profile.php">Змінити свої дані</a></p>
        <p><a href="logout.php">Вийти</a></p>
    </div>
</body>
</html>
