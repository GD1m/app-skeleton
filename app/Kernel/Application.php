<?php declare(strict_types=1);

namespace App\Kernel;

use DI\Container;
use DI\ContainerBuilder;

/**
 * Class Application
 * @package App\Kernel
 */
final class Application
{
    /**
     * @var Application
     */
    private static $instance;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var Container
     */
    private $container;

    /**
     * @return Application
     */
    public static function getInstance(): self
    {
        if (null === static::$instance) {
            throw new \RuntimeException('Application does not instantiated');
        }

        return static::$instance;
    }

    /**
     * @param Application $app
     * @return Application
     */
    private static function setInstance(Application $app): Application
    {
        return static::$instance = $app;
    }

    /**
     * @param string $basePath
     * @throws \Exception
     */
    public function __construct(string $basePath)
    {
        $this->container = $this->buildContainer($basePath . 'app/kernel/definitions.php');

        $this->setBasePath($basePath);

        $this->bindInstance();
    }

    /**
     * @return string
     */
    public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * @param string $basePath
     */
    private function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;

        $this->container->set('basePath', $this->basePath);
    }

    /**
     * @param string $definitionsPath
     * @return Container
     * @throws \Exception
     */
    private function buildContainer(string $definitionsPath): Container
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAnnotations(false);

        $containerBuilder->addDefinitions($definitionsPath);

        return $containerBuilder->build();
    }

    private function bindInstance(): void
    {
        self::setInstance($this);

        $this->container->set(__CLASS__, $this);
    }
}