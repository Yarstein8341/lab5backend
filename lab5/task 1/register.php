<?php
session_start();
include 'config.php';

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) {
    header('Location: welcome.php');
    exit();
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

$username_error = $email_error = $password_error = "";
$username = $email = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($username)) {
        $username_error = "Введіть логін";
    }

    if (empty($email)) {
        $email_error = "Введіть email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Некоректний формат email";
    }

    if (empty($password)) {
        $password_error = "Введіть пароль";
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $username_error = "Користувач з таким логіном або email вже існує";
        $stmt->close();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));

        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            echo "Помилка: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Реєстрація</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Логін:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                <span class="error"><?php echo $username_error; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                <span class="error"><?php echo $email_error; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
                <span class="error"><?php echo $password_error; ?></span>
            </div>
            <button type="submit">Зареєструватися</button>
        </form>
        <p>Вже маєте акаунт? <a href="index.php">Увійти</a></p>
    </div>
</body>
</html>
