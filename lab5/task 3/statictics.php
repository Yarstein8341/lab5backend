<?php
$dsn = 'mysql:host=localhost;dbname=company_db;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT AVG(salary) as avg_salary FROM employees';
    $result = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    $avg_salary = $result['avg_salary'];

    $sql = 'SELECT position, COUNT(*) as count FROM employees GROUP BY position';
    $positions = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Статистика</h2>";
    echo "<p>Середня заробітня плата: $" . number_format($avg_salary, 2) . "</p>";
    echo "<h3>Кількість працівників</h3>";
    echo "<ul>";
    foreach ($positions as $position) {
        echo "<li>{$position['position']}: {$position['count']} employees</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo 'Помилка з`єднання: ' . $e->getMessage();
}
?>
