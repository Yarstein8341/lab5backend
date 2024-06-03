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
    $name = $_POST['name'];
    $cost = $_POST['cost'];
    $kol = $_POST['kol'];
    $date = $_POST['date'];

    $stmt = $pdo->prepare("INSERT INTO tov (name, cost, kol, date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $cost, $kol, $date]);

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати запис</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Додати новий товар</h2>
        <form action="insert.php" method="post">
            <div class="form-group">
                <label for="name">Назва товару:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="cost">Вартість:</label>
                <input type="number" id="cost" name="cost" required>
            </div>
            <div class="form-group">
                <label for="kol">Кількість:</label>
                <input type="number" id="kol" name="kol" required>
            </div>
            <div class="form-group">
                <label for="date">Дата реалізації:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <button type="submit">Додати</button>
        </form>
    </div>
</body>
</html>
