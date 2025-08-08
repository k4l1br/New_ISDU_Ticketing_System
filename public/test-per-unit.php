<?php

// Simple test to debug the dashboard-per-unit endpoint

header('Content-Type: application/json');

// Include Laravel bootstrap
require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Set up the application
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test the endpoint
try {
    // Simulate a GET request to dashboard-per-unit
    $request = Illuminate\Http\Request::create('/dashboard-per-unit', 'GET');
    
    // Get a super admin user and authenticate
    $superAdmin = App\Models\User::where('role', 'super_admin')->first();
    
    if ($superAdmin) {
        auth()->login($superAdmin);
        echo "✅ Authenticated as: " . $superAdmin->name . " (Role: " . $superAdmin->role . ")\n";
        
        // Test the controller method directly
        $controller = new App\Http\Controllers\DashboardController();
        $result = $controller->getTicketsPerUnit();
        
        echo "✅ Controller method executed successfully\n";
        echo "📊 Response data:\n";
        echo $result->getContent();
        
    } else {
        echo "❌ No super admin user found in database\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

?>
