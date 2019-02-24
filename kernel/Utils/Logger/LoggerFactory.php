<?php declare(strict_types=1);

namespace Kernel\Utils\Logger;

use Kernel\Application;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * Class LoggerFactory
 * @package Kernel\Utils\Logger
 */
final class LoggerFactory
{
    /**
     * @var int
     */
    private $logLevel = Logger::WARNING;

    /**
     * @param ContainerInterface $container
     * @return Logger
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container): Logger
    {
        $logger = new Logger('app');

        $app = $container->get(Application::class);

        $streamHandler = new StreamHandler($app->basePath() . 'storage/logs/app.log', $this->logLevel);

        $logger->pushHandler($streamHandler);

        return $logger;
    }
}