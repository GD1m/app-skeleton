<?php declare(strict_types=1);

namespace App\Exceptions;

use Kernel\Exceptions\ResponsibleException;
use Rakit\Validation\ErrorBag;
use Throwable;

/**
 * Class ValidationException
 * @package App\Exceptions
 */
final class ValidationException extends \Exception implements ResponsibleException
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

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 400;
    }

    /**
     * @return array
     */
    public function getErrorData(): array
    {
        return [
            'error' => $this->getErrorBag()->all()[0],
        ];
    }
}