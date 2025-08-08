<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Set up database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'tick_system',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test ticket unit_responsible data
echo "Testing ticket unit_responsible data...\n\n";

$tickets = Capsule::table('tickets')->select('id', 'status', 'unit_responsible')->get();
echo "All tickets:\n";
foreach($tickets as $ticket) {
    echo "ID: {$ticket->id}, Status: {$ticket->status}, Unit Responsible: " . ($ticket->unit_responsible ?? 'NULL') . "\n";
}

echo "\nUnique unit_responsible values:\n";
$units = Capsule::table('tickets')->select('unit_responsible')->distinct()->whereNotNull('unit_responsible')->pluck('unit_responsible');
foreach($units as $unit) {
    echo "- " . $unit . "\n";
}

echo "\nTickets per unit breakdown:\n";
foreach($units as $unit) {
    $inProgress = Capsule::table('tickets')->where('unit_responsible', $unit)->where('status', 'In Progress')->count();
    $noAction = Capsule::table('tickets')->where('unit_responsible', $unit)->where('status', 'No Action')->count();
    $completed = Capsule::table('tickets')->where('unit_responsible', $unit)->where('status', 'Complete')->count();
    $total = $inProgress + $noAction + $completed;
    
    echo "Unit: $unit\n";
    echo "  In Progress: $inProgress\n";
    echo "  No Action: $noAction\n";
    echo "  Completed: $completed\n";
    echo "  Total: $total\n\n";
}

?>
