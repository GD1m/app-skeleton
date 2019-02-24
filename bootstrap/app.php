<?php declare(strict_types=1);

use Kernel\Application;

require __DIR__ . '/../vendor/autoload.php';

return new Application(
    \dirname(__DIR__) . DIRECTORY_SEPARATOR
);
