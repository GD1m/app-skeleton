<?php declare(strict_types=1);

namespace App\Kernel\Exceptions;

use App\Exceptions\ValidationException;
use App\Kernel\Http\Response\ErrorResponse;
use League\BooBoo\Formatter\JsonFormatter as BooBooJsonFormatter;

final class JsonFormatter extends BooBooJsonFormatter
{
    /**
     * @param \Throwable $e
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function format($e)
    {
        $statusCode = 500;

        if ($e instanceof \ErrorException) {
            $data = $this->handleErrors($e);
        } elseif ($e instanceof ValidationException) {
            $data = [
                'error' => $e->getErrorBag()->all()[0],
            ];

            $statusCode = 400;
        } else {
            $data = $this->formatExceptions($e);
        }

        $response = new ErrorResponse($data, $statusCode);

        $response->response();
    }
}