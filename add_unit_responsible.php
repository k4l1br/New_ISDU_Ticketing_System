<?php
$pdo = new PDO('mysql:host=localhost;dbname=tick_system', 'root', '');

// Check if unit_responsible column exists
$stmt = $pdo->query('DESCRIBE tickets');
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!in_array('unit_responsible', $columns)) {
    $pdo->exec('ALTER TABLE tickets ADD COLUMN unit_responsible VARCHAR(255) NULL AFTER status');
    echo "unit_responsible column added successfully to tickets table.\n";
} else {
    echo "unit_responsible column already exists in tickets table.\n";
}
?>
