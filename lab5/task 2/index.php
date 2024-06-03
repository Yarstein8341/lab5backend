<?php
session_start();
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lab5;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}
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
        <form action="delete.php" method="post">
            <input type="number" name="delete_id" placeholder="ID запису для видалення">
            <button type="submit">Вилучити запис</button>
        </form>
        <p><a href="insert.php"><button>Додати запис</button></a></p>
    </div>
    <div class="container">
        <h2>Товари</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Назва товару</th>
                <th>Вартість</th>
                <th>Кількість</th>
                <th>Дата реалізації</th>
            </tr>
            <?php
            $sql = "SELECT * FROM tov";
            $result = $pdo->query($sql);

            foreach($result as $row) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['cost']."</td>";
                echo "<td>".$row['kol']."</td>";
                echo "<td>".$row['date']."</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
