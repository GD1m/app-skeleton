<?php declare(strict_types=1);

namespace Kernel\Exceptions\Handler;

use App\Exceptions\Handler;
use Kernel\Application;
use League\BooBoo\Handler\LogHandler as BooBooLogHandler;

/**
 * Class LogHandler
 * @package Kernel\Exceptions\Handler
 */
final class LogHandler extends BooBooLogHandler
{
    /**
     * @param \Throwable $e
     */
    public function handle($e): void
    {
        if ($e instanceof \ErrorException) {
            $this->handleErrorException($e);
            return;
        }

        if ($this->shouldBeLogged($e)) {
            $this->logger->critical($e->getMessage() . $e->getTraceAsString());
        }
    }

    /**
     * @param \Throwable $e
     * @return bool
     */
    private function shouldBeLogged(\Throwable $e): bool
    {
        $className = \get_class($e);

        $handler = Application::getInstance()->container()->get(Handler::class);

        return !\in_array($className, $handler->shouldNotBeLogged(), true);
    }
}