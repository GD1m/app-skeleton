<?php declare(strict_types=1);

return [
    'name' => 'TODO Application Migrations',
    'migrations_namespace' => 'App\Database\Migrations',
    'table_name' => 'migrations',
    'column_name' => 'version',
    'column_length' => 255,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => '/app/Database/Migrations',
    'all_or_nothing' => true,
];