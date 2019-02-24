<?php declare(strict_types=1);

namespace Kernel\Http\Router;

use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use function FastRoute\simpleDispatcher;

/**
 * Class RouterFactory
 * @package Kernel\Http\Router
 */
final class RouterFactory
{
    /**
     * @param ContainerInterface $container
     * @return Dispatcher
     */
    public function __invoke(ContainerInterface $container): Dispatcher
    {
        return simpleDispatcher(require $container->get('basePath') . 'routes/v1.php');
    }
}