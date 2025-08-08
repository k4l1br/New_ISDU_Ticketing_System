<?php
$pdo = new PDO('mysql:host=localhost;dbname=tick_system', 'root', '');
$stmt = $pdo->query('SELECT COUNT(*) as count FROM tickets');
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Tickets table has " . $result['count'] . " records.\n";

if ($result['count'] > 0) {
    $stmt = $pdo->query('SELECT id, full_name, status, unit_responsible FROM tickets LIMIT 3');
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Sample tickets:\n";
    foreach($tickets as $ticket) {
        echo "- ID: {$ticket['id']}, Name: {$ticket['full_name']}, Status: {$ticket['status']}, Unit: {$ticket['unit_responsible']}\n";
    }
}
?>
