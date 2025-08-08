<?php
$pdo = new PDO('mysql:host=localhost;dbname=tick_system', 'root', '');
$stmt = $pdo->query('SELECT COUNT(*) as count FROM offices');
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo 'Offices table has ' . $result['count'] . " records.\n";

$stmt = $pdo->query('SELECT name, username FROM offices LIMIT 3');
$offices = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Sample offices:\n";
foreach($offices as $office) {
    echo "- {$office['name']} (username: {$office['username']})\n";
}
?>
