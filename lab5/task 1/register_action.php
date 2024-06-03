<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $country = $_POST['country'];

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Помилка підключення: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Користувач з таким логіном або email вже існує.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, first_name, last_name, age, gender, phone, city, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $username, $password, $email, $first_name, $last_name, $age, $gender, $phone, $city, $country);

        if ($stmt->execute()) {
            echo "Реєстрація успішна. <a href='index.php'>Вхід</a>";
        } else {
            echo "Помилка реєстрації: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>
