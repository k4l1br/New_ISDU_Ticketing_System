<?php
// Script to update unitResponsible to unit_responsible in DashboardController

$file = __DIR__ . '/app/Http/Controllers/DashboardController.php';
$content = file_get_contents($file);

// Replace all instances of unitResponsible with unit_responsible
$content = str_replace('unitResponsible', 'unit_responsible', $content);

file_put_contents($file, $content);
echo "DashboardController updated successfully!\n";
?>
