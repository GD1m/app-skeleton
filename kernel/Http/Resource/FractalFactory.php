<?php declare(strict_types=1);

namespace Kernel\Http\Resource;

use Kernel\Http\Resource\Serializer\NamedDataArraySerializer;
use League\Fractal\Manager;

/**
 * Class FractalFactory
 * @package Kernel\Http\Resource
 */
final class FractalFactory
{
    /**
     * @return Manager
     */
    public function __invoke(): Manager
    {
        $fractal = new Manager();

        $fractal->setSerializer(new NamedDataArraySerializer());

        return $fractal;
    }
}