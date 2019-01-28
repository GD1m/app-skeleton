<?php declare(strict_types=1);

namespace App\Kernel\Utils\Validation;

use Psr\Container\ContainerInterface;
use Rakit\Validation\Validator;

/**
 * Class ValidatorFactory
 * @package App\Kernel\Validation
 */
final class ValidatorFactory
{
    /**
     * @param ContainerInterface $container
     * @return Validator
     */
    public function __invoke(ContainerInterface $container): Validator
    {
        return new Validator();
    }
}