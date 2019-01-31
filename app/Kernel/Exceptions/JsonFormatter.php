<?php declare(strict_types=1);

namespace App\Kernel\Exceptions;

use App\Exceptions\Responsible;
use App\Kernel\Application;
use App\Kernel\Http\Response\ErrorResponse;
use League\BooBoo\Formatter\JsonFormatter as BooBooJsonFormatter;

/**
 * Class JsonFormatter
 * @package App\Kernel\Exceptions
 */
final class JsonFormatter extends BooBooJsonFormatter
{
    /**
     * @param \Throwable $e
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \App\Exceptions\ConfigMissedException
     */
    public function format($e)
    {
        $statusCode = 500;

        if ($e instanceof Responsible) {
            $statusCode = $e->getStatusCode();
            $data = $e->getErrorData();
        } elseif ('production' === Application::getInstance()->environment()) {
            $data = ['error' => 'Something went wrong'];
        } elseif ($e instanceof \ErrorException) {
            $data = $this->handleErrors($e);
        } else {
            $data = $this->formatExceptions($e);
        }

        $response = new ErrorResponse($data, $statusCode);

        $response->response();
    }

    /**
     * @param \Throwable $e
     * @return array
     */
    protected function formatExceptions($e): array
    {
        $type = \get_class($e);

        $message = $e->getMessage();
        $file = $e->getFile();
        $line = $e->getLine();
        $trace = $e->getTraceAsString();

        $error = [
            'severity' => 'Exception',
            'type' => $type,
            'code' => $e->getCode(),
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'trace' => $trace,
        ];

        if ($e->getPrevious()) {
            $error = [$error];
            $newError = $this->formatExceptions($e->getPrevious());
            array_unshift($error, $newError);
        }

        return $error;
    }
}