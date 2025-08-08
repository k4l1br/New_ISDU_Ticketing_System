<?php
// Simple script to add unit column to users table

// Database configuration from Laravel's .env
$host = 'localhost';
$dbname = 'tick_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if unit column exists
    $stmt = $pdo->query('DESCRIBE users');
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('unit', $columns)) {
        $pdo->exec('ALTER TABLE users ADD COLUMN unit VARCHAR(255) NULL AFTER role');
        echo "Unit column added successfully to users table.\n";
    } else {
        echo "Unit column already exists in users table.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
