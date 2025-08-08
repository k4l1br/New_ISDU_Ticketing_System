<?php
// Simple script to create offices table

// Database configuration from Laravel's .env
$host = 'localhost';
$dbname = 'tick_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if offices table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'offices'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        $createTableSQL = "
        CREATE TABLE `offices` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `username` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `email` varchar(255) DEFAULT NULL,
            `email_verified_at` timestamp NULL DEFAULT NULL,
            `head_of_office` varchar(255) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT '1',
            `remember_token` varchar(100) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `offices_username_unique` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        $pdo->exec($createTableSQL);
        echo "Offices table created successfully.\n";
    } else {
        echo "Offices table already exists.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
