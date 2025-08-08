<?php

// Simple test script to check dashboard API endpoints

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Models\User;

// Bootstrap Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Request::create('/dashboard-data', 'GET');
$response = $kernel->handle($request);

echo "=== Testing Dashboard API Endpoints ===\n\n";

// Test 1: Dashboard data endpoint
echo "1. Testing /dashboard-data endpoint:\n";
$request = Request::create('/dashboard-data', 'GET');

try {
    // We need to simulate authentication
    $superAdminUser = User::where('role', 'super_admin')->first();
    if ($superAdminUser) {
        auth()->login($superAdminUser);
        
        $controller = new DashboardController();
        $response = $controller->getData();
        echo "Response: " . $response->getContent() . "\n\n";
    } else {
        echo "No super admin user found in database!\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Per unit data endpoint
echo "2. Testing /dashboard-per-unit endpoint:\n";
try {
    if ($superAdminUser) {
        $response = $controller->getTicketsPerUnit();
        echo "Response: " . json_encode($response) . "\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Tasks report endpoint
echo "3. Testing /dashboard-tasks-report endpoint:\n";
try {
    if ($superAdminUser) {
        $request = new Request(['range' => 'weekly']);
        $response = $controller->tasksReport($request);
        echo "Response: " . $response->getContent() . "\n\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "=== Test completed ===\n";
?>
