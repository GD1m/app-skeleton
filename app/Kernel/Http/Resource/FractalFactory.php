<?php declare(strict_types=1);

namespace App\Kernel\Http\Resource;

use App\Kernel\Http\Resource\Serializer\NamedDataArraySerializer;
use League\Fractal\Manager;

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

        $fractal->setSerializer(new NamedDataArraySerializer());

        return $fractal;
    }
}