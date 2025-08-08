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

// Update users with unit information
$updates = [
    'admin.isdu@isdu.com' => 'ISDU (INFORMATION SYSTEMS DEVELOPMENT UNIT)',
    'admin.nmu@isdu.com' => 'NMU (NETWORK MANAGEMENT UNIT)',
    'admin.repair@isdu.com' => 'REPAIR',
    'admin.mb@isdu.com' => 'MB (MANAGEMENT BRANCH)',
];

foreach ($updates as $email => $unit) {
    $updated = Capsule::table('users')
        ->where('email', $email)
        ->update(['unit' => $unit]);
    
    if ($updated) {
        echo "Updated $email with unit: $unit\n";
    } else {
        echo "No user found with email: $email\n";
    }
}

echo "All admin users updated with unit information.\n";
?>
