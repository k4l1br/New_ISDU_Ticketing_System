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

// Test super admin dashboard queries directly
echo "Testing super admin dashboard queries...\n\n";

$totalTickets = Capsule::table('tickets')->count();
echo "Total tickets: $totalTickets\n";

$inProgress = Capsule::table('tickets')->where('status', 'like', '%in progress%')->count();
echo "In Progress tickets: $inProgress\n";

$noAction = Capsule::table('tickets')->where('status', 'like', '%no action%')->count();
echo "No Action tickets: $noAction\n";

$completed = Capsule::table('tickets')->where('status', 'like', '%complete%')->count();
echo "Completed tickets: $completed\n";

echo "\nTicket statuses in database:\n";
$statuses = Capsule::table('tickets')->select('status')->get();
foreach($statuses as $status) {
    echo "- " . $status->status . "\n";
}

?>
