<?php declare(strict_types=1);

namespace App\Kernel;

use App\Exceptions\ConfigMissedException;
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use League\BooBoo\BooBoo;

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
     * @var array
     */
    private $config;

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
        $this->setErrorHandlerSettings();

        $this->container = $this->buildContainer($basePath . 'app/kernel/definitions.php');

        $this->setBasePath($basePath);

        $this->bindInstance();

        $this->registerErrorHandler();

        $this->loadEnvData();

        $this->config = $this->loadConfig();

        $this->setTimeZone();
    }

    /**
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * @return string
     * @throws ConfigMissedException
     */
    public function environment(): string
    {
        return $this->config('env');
    }

    /**
     * @param string $key
     * @return mixed
     * @throws ConfigMissedException
     */
    public function config(string $key)
    {
        if (!isset($this->config[$key]) || false === $this->config[$key]) {
            throw new ConfigMissedException(sprintf('%s missed', $key));
        }

        return $this->config[$key];
    }

    private function setErrorHandlerSettings(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    }

    private function setTimeZone(): void
    {
        date_default_timezone_set($this->config('timeZone'));
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

    /**
     * @return BooBoo
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function registerErrorHandler(): BooBoo
    {
        return $this->container->make(BooBoo::class);
    }

    private function loadEnvData(): void
    {
        $this->container->make(Dotenv::class);
    }

    /**
     * @return array
     */
    private function loadConfig(): array
    {
        return require $this->basePath . '/config/app.php';
    }
}