<?php

$host = '127.0.0.1';
$db = 'laravel_eatera_fe';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $result = $pdo->query('SELECT COUNT(*) as total FROM users')->fetch();
    echo "Total user di database: " . $result['total'] . PHP_EOL;
    
    if ($result['total'] > 0) {
        echo PHP_EOL . "Daftar user yang ada:\n";
        echo str_repeat("-", 60) . PHP_EOL;
        $rows = $pdo->query('SELECT id, name, email, role FROM users ORDER BY id')->fetchAll();
        foreach ($rows as $row) {
            echo "[" . $row['id'] . "] " . str_pad($row['name'], 20) . " | " . $row['email'] . " | " . $row['role'] . PHP_EOL;
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
}
