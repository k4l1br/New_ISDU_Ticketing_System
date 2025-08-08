<?php
$pdo = new PDO('mysql:host=localhost;dbname=tick_system', 'root', '');
$stmt = $pdo->query('DESCRIBE tickets');
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Tickets table columns:\n";
foreach($columns as $column) {
    echo "- {$column['Field']} ({$column['Type']})\n";
}
?>
