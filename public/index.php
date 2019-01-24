<?php declare(strict_types=1);

use App\Kernel\Http\Kernel;
use Zend\Diactoros\ServerRequest;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

/** @var \App\Kernel\Application $app */
$app = require './../bootstrap/app.php';

$container = $app->container();

$httpKernel = $container->get(Kernel::class);

$response = $httpKernel->handle(
    $container->get(ServerRequest::class)
);

$emitter = $container->get(SapiEmitter::class);

$emitter->emit($response);