<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $count = \DB::table('properties')->count();
    echo "Properties count: " . $count . "\n";
    
    // Check if properties table exists
    $tables = \DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    echo "\nTables in database:\n";
    foreach ($tables as $table) {
        echo "- " . $table->table_name . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}