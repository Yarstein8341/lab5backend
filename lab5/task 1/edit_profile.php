<?php
session_start();
include 'config.php';

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != true) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT username, email, first_name, last_name, age, gender, phone, city, country FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $first_name, $last_name, $age, $gender, $phone, $city, $country);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $country = $_POST['country'];

    $stmt = $conn->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ?, age = ?, gender = ?, phone = ?, city = ?, country = ? WHERE id = ?");
    $stmt->bind_param("ssssssssi", $email, $first_name, $last_name, $age, $gender, $phone, $city, $country, $user_id);

    if ($stmt->execute()) {
        echo "Дані успішно оновлено.";
    } else {
        echo "Помилка оновлення даних: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагування профілю</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Редагування профілю</h2>
        <form action="edit_profile.php" method="post">
            <div class="form-group">
                <label for="username">Логін:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="first_name">Ім'я:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Прізвище:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Вік:</label>
                <input type="number" id="age" name="age" value="<?php echo $age; ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Стать:</label>
                <select id="gender" name="gender" required>
                    <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Чоловік</option>
                    <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Жінка</option>
                </select>
            </div>
            <div class="form-group">
                <label for="phone">Телефон:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" required>
            </div>
            <div class="form-group">
                <label for="city">Місто:</label>
                <input type="text" id="city" name="city" value="<?php echo $city; ?>" required>
            </div>
            <div class="form-group">
                <label for="country">Країна:</label>
                <input type="text" id="country" name="country" value="<?php echo $country; ?>" required>
            </div>
            <button type="submit">Оновити дані</button>
        </form>
        <p><a href="welcome.php">Повернутись на головну</a></p>
    </div>
</body>
</html>
