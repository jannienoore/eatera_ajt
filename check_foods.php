<?php
$host = '127.0.0.1';
$db = 'laravel_eatera_fe';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $pass);
    $result = $pdo->query('SELECT COUNT(*) as total FROM foods')->fetch();
    echo 'Total data makanan: ' . $result['total'] . PHP_EOL;
    
    if ($result['total'] > 0) {
        echo PHP_EOL . 'Data makanan yang ada:' . PHP_EOL;
        $rows = $pdo->query('SELECT id, name, calories FROM foods LIMIT 10')->fetchAll();
        foreach ($rows as $row) {
            echo '- ' . $row['name'] . ' (' . $row['calories'] . ' kal)' . PHP_EOL;
        }
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
