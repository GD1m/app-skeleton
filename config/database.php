<?php declare(strict_types=1);

return [
    'driver' => getenv('DB_DRIVER'),
    'host' => getenv('DB_HOST'),
    'user' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'dbname' => getenv('DB_NAME'),
];
