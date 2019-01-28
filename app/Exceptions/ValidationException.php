<?php declare(strict_types=1);

namespace App\Exceptions;

use Monolog\Logger;
use Rakit\Validation\ErrorBag;
use Throwable;

/**
 * Class ValidationException
 * @package App\Exceptions
 */
final class ValidationException extends \Exception
{
    /**
     * @var ErrorBag
     */
    private $errorBag;

    /**
     * @param ErrorBag $errorBag
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(ErrorBag $errorBag, int $code = 0, Throwable $previous = null)
    {
        $this->errorBag = $errorBag;

        $errors = $errorBag->all();

        parent::__construct($errors[0], $code, $previous);
    }

    /**
     * @return ErrorBag
     */
    public function getErrorBag(): ErrorBag
    {
        return $this->errorBag;
    }
}