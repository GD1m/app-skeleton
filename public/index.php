<?php declare(strict_types=1);

use App\Kernel\Http\Kernel;
use Psr\Http\Message\ServerRequestInterface;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

/** @var \App\Kernel\Application $app */
$app = require './../bootstrap/app.php';

$container = $app->container();

$httpKernel = $container->get(Kernel::class);

$response = $httpKernel->handle(
    $container->get(ServerRequestInterface::class)
);

$emitter = $container->get(SapiEmitter::class);

$emitter->emit($response);