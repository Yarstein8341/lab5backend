<?php
$dsn = 'mysql:host=localhost;dbname=company_db;charset=utf8';
$username = 'root';
$password = '';

function getPDOConnection($dsn, $username, $password) {
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Помилка з`єднання: ' . $e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee'])) {
    try {
        $pdo = getPDOConnection($dsn, $username, $password);
        $sql = 'INSERT INTO employees (name, position, salary) VALUES (?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['name'], $_POST['position'], $_POST['salary']]);
        echo "Новий працівник доданий успішно.";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_employee'])) {
    try {
        $pdo = getPDOConnection($dsn, $username, $password);
        $sql = 'UPDATE employees SET name = ?, position = ?, salary = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['name'], $_POST['position'], $_POST['salary'], $_POST['id']]);
        echo "Працівник видалений успішно.";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_employee'])) {
    try {
        $pdo = getPDOConnection($dsn, $username, $password);
        $sql = 'DELETE FROM employees WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['id']]);
        echo "Працівник видалений успішно.";
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

try {
    $pdo = getPDOConnection($dsn, $username, $password);
    $sql = 'SELECT * FROM employees';
    $result = $pdo->query($sql);
    $employees = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}

$statistics = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['get_statistics'])) {
    try {
        $pdo = getPDOConnection($dsn, $username, $password);
        $sql = 'SELECT AVG(salary) AS average_salary, position, COUNT(*) AS count FROM employees GROUP BY position';
        $result = $pdo->query($sql);
        $stats = $result->fetchAll(PDO::FETCH_ASSOC);
        $statistics .= '<h3>Статистика</h3>';
        $statistics .= '<p>Середня заробітна плата: ' . $stats[0]['average_salary'] . '</p>';
        $statistics .= '<table>';
        $statistics .= '<tr><th>Посада</th><th>Кількість працівників</th></tr>';
        foreach ($stats as $stat) {
            $statistics .= '<tr><td>' . htmlspecialchars($stat['position']) . '</td><td>' . htmlspecialchars($stat['count']) . '</td></tr>';
        }
        $statistics .= '</table>';
    } catch (PDOException $e) {
        $statistics = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>База даних працівників</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-container { margin-bottom: 20px; }
        .form-container form { display: flex; flex-direction: column; }
        .form-container form input, .form-container form button { margin: 5px 0; padding: 8px; }
        .form-container form button { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .form-container form button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h2>База даних працівників</h2>
    
    <div class="form-container">
        <h3>Додавання нового працівника</h3>
        <form method="POST">
            <input type="text" name="name" placeholder="Ім'я" required>
            <input type="text" name="position" placeholder="Посада" required>
            <input type="number" name="salary" placeholder="Зарплата" required>
            <button type="submit" name="add_employee">Додати</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Код</th>
                <th>Ім'я</th>
                <th>Посада</th>
                <th>Зарплата</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo htmlspecialchars($employee['id']); ?></td>
                    <td><?php echo htmlspecialchars($employee['name']); ?></td>
                    <td><?php echo htmlspecialchars($employee['position']); ?></td>
                    <td><?php echo htmlspecialchars($employee['salary']); ?></td>
                    <td>
                        <button onclick="populateEditForm('<?php echo htmlspecialchars(json_encode($employee)); ?>')">Змінити</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($employee['id']); ?>">
                            <button type="submit" name="delete_employee">Видалити</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="form-container" id="editFormContainer" style="display:none;">
        <h3>Змінення даних працівника</h3>
        <form method="POST">
            <input type="hidden" name="id" id="editId">
            <input type="text" name="name" id="editName" placeholder="Ім'я" required>
            <input type="text" name="position" id="editPosition" placeholder="Посада" required>
            <input type="number" name="salary" id="editSalary" placeholder="Зарплата" required>
            <button type="submit" name="edit_employee">Оновити дані</button>
        </form>
    </div>

    <div class="form-container">
        <h3>Отримання статистики</h3>
        <form method="POST">
            <button type="submit" name="get_statistics">Вивести статистику</button>
        </form>
    </div>

    <?php if (!empty($statistics)) echo $statistics; ?>

    <script>
        function populateEditForm(employee) {
            const employeeData = JSON.parse(employee);
            document.getElementById('editId').value = employeeData.id;
            document.getElementById('editName').value = employeeData.name;
            document.getElementById('editPosition').value = employeeData.position;
            document.getElementById('editSalary').value = employeeData.salary;
            document.getElementById('editFormContainer').style.display = 'block';
        }
    </script>
</body>
</html>
