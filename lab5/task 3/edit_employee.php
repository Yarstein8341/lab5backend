<?php
$dsn = 'mysql:host=localhost;dbname=company_db;charset=utf8';
$username = 'root';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'UPDATE employees SET name = ?, position = ?, salary = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['name'], $_POST['position'], $_POST['salary'], $_POST['id']]);
        echo "Оновлення даних успішно.";
    } catch (PDOException $e) {
        echo 'Помилка з`єднання: ' . $e->getMessage();
    }
} else {
    if (!isset($_GET['id'])) {
        echo "Ідентифікатор працівника не надано.";
        exit();
    }

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT * FROM employees WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['id']]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$employee) {
            echo "Працівника не знайдено.";
            exit();
        }
    } catch (PDOException $e) {
        echo 'Помилка з`єднання: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Змінення даних працівника</title>
</head>
<body>
    <h2>Зміна даних</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($employee['id']); ?>">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required><br>
        <label for="position">Position:</label><br>
        <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required><br>
        <label for="salary">Salary:</label><br>
        <input type="number" id="salary" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>" required><br>
        <input type="submit" value="Оновити дані">
    </form>
</body>
</html>
