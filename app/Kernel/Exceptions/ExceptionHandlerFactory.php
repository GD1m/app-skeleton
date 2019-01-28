<?php declare(strict_types=1);

namespace App\Kernel\Exceptions;

use League\BooBoo\BooBoo;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ExceptionHandlerFactory
 * @package App\Kernel\Exceptions
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