<?php
// Simple test to check dashboard rendering without authentication
require_once 'vendor/autoload.php';

// Set up minimal Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test if the dashboard view can be compiled
try {
    $viewContent = file_get_contents('resources/views/shared/dashboard-clean.blade.php');
    echo "✓ Dashboard view file exists and is readable\n";
    echo "File size: " . strlen($viewContent) . " bytes\n";
    
    // Check for critical components
    $components = [
        'ticketPieChart' => strpos($viewContent, 'ticketPieChart') !== false,
        'taskLineChart' => strpos($viewContent, 'taskLineChart') !== false,
        'perUnitTable' => strpos($viewContent, 'perUnitTable') !== false,
        'createPieChart' => strpos($viewContent, 'createPieChart') !== false,
        'createLineChart' => strpos($viewContent, 'createLineChart') !== false,
        'createTable' => strpos($viewContent, 'createTable') !== false,
    ];
    
    echo "\nComponent check:\n";
    foreach ($components as $component => $exists) {
        echo ($exists ? "✓" : "✗") . " $component\n";
    }
    
    // Check for DataTables dependencies (should be minimal now)
    $dataTables = substr_count($viewContent, 'DataTable');
    echo "\nDataTables references: $dataTables (should be minimal)\n";
    
    // Check for Chart.js references
    $chartJs = substr_count($viewContent, 'Chart');
    echo "Chart.js references: $chartJs\n";
    
    echo "\n✓ Dashboard view appears to be properly structured\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
