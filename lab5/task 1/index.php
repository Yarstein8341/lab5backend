<?php
session_start();

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) {
    header('Location: welcome.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід на сайт</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Вхід на сайт</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Логін:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Увійти</button>
        </form>
        <p>Немає акаунту? <a href="register.php">Реєстрація</a></p>
    </div>
</body>
</html>
