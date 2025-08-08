<?php
// Script to update specific unitResponsible references in TicketController

$file = __DIR__ . '/app/Http/Controllers/ticketController.php';
$content = file_get_contents($file);

// Only replace references to $ticket->unitResponsible and database field references
// Keep form field names as unitResponsible for validation
$replacements = [
    'ticket->unitResponsible' => 'ticket->unit_responsible',
    "->where('unitResponsible'" => "->where('unit_responsible'",
    "->orWhere('unitResponsible'" => "->orWhere('unit_responsible'",
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($file, $content);
echo "TicketController updated successfully!\n";
?>
