<?php declare(strict_types=1);

namespace App\Kernel\Exceptions;

use App\Exceptions\ValidationException;
use League\BooBoo\Handler\LogHandler as BooBooLogHandler;

/**
 * Class LogHandler
 * @package App\Kernel\Exceptions
 */
final class LogHandler extends BooBooLogHandler
{
    /**
     * @var array
     */
    private $shouldNotBeLogged = [
        ValidationException::class,
    ];

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

        return !\in_array($className, $this->shouldNotBeLogged, true);
    }
}