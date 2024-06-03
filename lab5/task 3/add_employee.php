<?php
$dsn = 'mysql:host=localhost;dbname=company_db;charset=utf8';
$username = 'root';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'INSERT INTO employees (name, position, salary) VALUES (?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['name'], $_POST['position'], $_POST['salary']]);
        echo "Новий працівник доданий успішно.";
    } catch (PDOException $e) {
        echo 'Помилка з`єднання: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Додавання нового працівника</title>
</head>
<body>
    <h2>Додати нового працівника</h2>
    <form method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="position">Position:</label><br>
        <input type="text" id="position" name="position" required><br>
        <label for="salary">Salary:</label><br>
        <input type="number" id="salary" name="salary" required><br>
        <input type="submit" value="Додати">
    </form>
</body>
</html>
