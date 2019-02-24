<?php declare(strict_types=1);

namespace Kernel\Exceptions\Handler;

use League\BooBoo\BooBoo;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ExceptionHandlerFactory
 * @package Kernel\Exceptions\Handler
 */
final class ExceptionHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BooBoo
     * @throws \League\BooBoo\Exception\NoFormattersRegisteredException
     */
    public function __invoke(ContainerInterface $container): BooBoo
    {
        $booboo = new BooBoo([
            new JsonFormatter(),
        ], [
            new LogHandler($container->get(LoggerInterface::class)),
        ]);

        $booboo->register();

        return $booboo;
    }
}