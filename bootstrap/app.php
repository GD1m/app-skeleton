<?php declare(strict_types=1);

use App\Kernel\Application;

require __DIR__ . '/../vendor/autoload.php';

return new Application(
    \dirname(__DIR__) . DIRECTORY_SEPARATOR
);

function dd($r) // TODO delete
{
    echo '<pre>';

    print_r($r);
    exit;
}
