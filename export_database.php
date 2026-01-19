<?php

$host = '127.0.0.1';
$db = 'laravel_eatera_fe';
$user = 'root';
$pass = '';
$output_file = 'laravel_eatera_fe_updated.sql';

try {
    $pdo = new PDO('mysql:host=' . $host . ';dbname=' . $db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all table names
    $tables = $pdo->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$db'")->fetchAll(PDO::FETCH_COLUMN);
    
    $sql = "-- Database: $db\n";
    $sql .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    foreach ($tables as $table) {
        // Get CREATE TABLE statement
        $createTable = $pdo->query("SHOW CREATE TABLE $table")->fetch(PDO::FETCH_ASSOC);
        $sql .= $createTable['Create Table'] . ";\n\n";
        
        // Get data
        $rows = $pdo->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($rows)) {
            $columns = array_keys($rows[0]);
            $sql .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES\n";
            
            $values = [];
            foreach ($rows as $row) {
                $val = [];
                foreach ($row as $v) {
                    if ($v === null) {
                        $val[] = 'NULL';
                    } else {
                        $val[] = "'" . addslashes($v) . "'";
                    }
                }
                $values[] = "(" . implode(', ', $val) . ")";
            }
            
            $sql .= implode(",\n", $values) . ";\n\n";
        }
    }
    
    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
    file_put_contents($output_file, $sql);
    echo "✅ Database exported successfully to: $output_file\n";
    echo "File size: " . filesize($output_file) . " bytes\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
