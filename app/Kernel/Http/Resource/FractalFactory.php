<?php declare(strict_types=1);

namespace App\Kernel\Http\Resource;

use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;

/**
 * Class FractalFactory
 * @package App\Kernel\Http\Resource
 */
final class FractalFactory
{
    /**
     * @return Manager
     */
    public function __invoke(): Manager
    {
        $fractal = new Manager();

        $fractal->setSerializer(new ArraySerializer());

        return $fractal;
    }
}