<?php
session_start();
include 'config.php';

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) {
    header('Location: welcome.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


    if ($conn->connect_error) {
        die("Помилка підключення: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {

        $stmt->bind_result($user_id, $db_username, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION['authenticated'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $db_username;

            header('Location: welcome.php');
            exit();
        } else {
            $error_message = "Неправильний логін або пароль";
        }
    } else {
        $error_message = "Неправильний логін або пароль";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Вхід</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
        <p>Не маєте акаунту? <a href="register.php">Зареєструватися</a></p>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
    </div>
</body>
</html>
