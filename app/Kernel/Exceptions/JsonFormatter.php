<?php declare(strict_types=1);

namespace App\Kernel\Exceptions;

use App\Exceptions\ValidationException;
use League\BooBoo\Formatter\JsonFormatter as BooBooJsonFormatter;

final class JsonFormatter extends BooBooJsonFormatter
{
    /**
     * @param \Throwable $e
     * @return string
     */
    public function format($e): string
    {
        if ($e instanceof \ErrorException) {
            $response = $this->handleErrors($e);
        } elseif ($e instanceof ValidationException) {
            $response = [
                'error' => $e->getErrorBag()->all()[0],
            ];
        } else {
            $response = $this->formatExceptions($e);
        }

        return json_encode($response);
    }
}