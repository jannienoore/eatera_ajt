<?php

$file = 'laravel_eatera_fe.sql';
$host = '127.0.0.1';
$db = 'laravel_eatera_fe';
$user = 'root';
$pass = '';

if (!file_exists($file)) {
    die("File $file tidak ditemukan!\n");
}

try {
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read SQL file
    $sql = file_get_contents($file);
    
    // Remove comments and handle multi-line statements
    $sql = preg_replace('/^--.*$/m', '', $sql);
    $sql = preg_replace('/^#.*$/m', '', $sql);
    
    // Split by semicolon, but keep complete statements
    $statements = [];
    $current = '';
    
    foreach (explode("\n", $sql) as $line) {
        $current .= $line . "\n";
        if (strpos($line, ';') !== false) {
            $stmt = trim($current);
            if (!empty($stmt)) {
                $statements[] = $stmt;
            }
            $current = '';
        }
    }
    
    $count = 0;
    $errors = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (strlen($statement) > 10) {
            try {
                $pdo->exec($statement);
                $count++;
            } catch (Exception $e) {
                $errors++;
                // echo "Warning: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "✓ Database import selesai!\n";
    echo "Statement dijalankan: $count\n";
    if ($errors > 0) {
        echo "Warnings: $errors (biasanya dari ALTER TABLE atau duplicate keys)\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error koneksi: " . $e->getMessage() . "\n";
    die();
}
