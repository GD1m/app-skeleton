<?php declare(strict_types=1);

namespace Kernel\Env;

use Kernel\Application;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

/**
 * Class DotenvFactory
 * @package Kernel\Env
 */
final class DotenvFactory
{
    /**
     * @param ContainerInterface $container
     * @return Dotenv
     */
    public function __invoke(ContainerInterface $container): Dotenv
    {
        $app = $container->get(Application::class);

        $dotenv = Dotenv::create($app->basePath());

        $dotenv->load();

        return $dotenv;
    }
}