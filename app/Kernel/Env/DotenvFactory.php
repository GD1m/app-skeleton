<?php declare(strict_types=1);

namespace App\Kernel\Env;

use App\Kernel\Application;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

/**
 * Class DotenvFactory
 * @package App\Kernel\Env
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