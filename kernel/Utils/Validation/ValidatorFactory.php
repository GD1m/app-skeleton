<?php declare(strict_types=1);

namespace Kernel\Utils\Validation;

use Kernel\Utils\Validation\Rules\UniqueRule;
use Psr\Container\ContainerInterface;
use Rakit\Validation\Validator;

/**
 * Class ValidatorFactory
 * @package Kernel\Validation
 */
final class ValidatorFactory
{
    /**
     * @param ContainerInterface $container
     * @return Validator
     * @throws \Rakit\Validation\RuleQuashException
     */
    public function __invoke(ContainerInterface $container): Validator
    {
        $validator = new Validator();

        $validator->addValidator('unique', $container->get(UniqueRule::class));

        return $validator;
    }
}