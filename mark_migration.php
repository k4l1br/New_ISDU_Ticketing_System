<?php
$pdo = new PDO('mysql:host=localhost;dbname=tick_system', 'root', '');
$pdo->exec("INSERT INTO migrations (migration, batch) VALUES ('2025_07_18_064032_add_missing_auth_fields_to_offices', 1)");
echo "Migration marked as completed\n";
?>
