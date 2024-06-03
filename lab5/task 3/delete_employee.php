<?php
$dsn = 'mysql:host=localhost;dbname=company_db;charset=utf8';
$username = 'root';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'DELETE FROM employees WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['id']]);
        echo "Працівник успішно видалений.";
    } catch (PDOException $e) {
        echo 'Помилка з`єднання: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Видалення працівника</title>
</head>
<body>
    <h2>Видалити працівника</h2>
    <form method="POST">
        <label for="id">Код працівника:</label><br>
        <input type="number" id="id" name="id" required><br>
        <input type="submit" value="Видалити">
    </form>
</body>
</html>
