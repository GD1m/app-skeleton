<?php

namespace App\Services\Security;


use App\Exceptions\InfiniteLoopException;
use Kernel\Utils\String\RandomStringGenerator;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TokenGenerator
 * @package App\Services\Security
 */
class TokenGenerator
{
    /**
     * @var RandomStringGenerator
     */
    private $stringGenerator;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param RandomStringGenerator $stringGenerator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RandomStringGenerator $stringGenerator, EntityManagerInterface $entityManager)
    {
        $this->stringGenerator = $stringGenerator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $entityClass
     * @param string $fieldName
     * @param int $length
     * @return string
     * @throws InfiniteLoopException
     * @throws \Exception
     */
    public function generateUniqueToken($entityClass, $fieldName = 'token', $length = 50): string
    {
        $i = 0;
        $maxIterations = 10;

        do {
            $token = $this->stringGenerator->generate($length);
            $i++;
        } while (
            $i <= $maxIterations
            &&
            $this->checkTokenExists($entityClass, $fieldName, $token)
        );

        if ($i === $maxIterations) {
            throw new InfiniteLoopException();
        }

        return $token;
    }

    /**
     * @param $entityClass
     * @param $fieldName
     * @param $token
     * @return bool
     */
    private function checkTokenExists($entityClass, $fieldName, $token): bool
    {
        return (bool)$this->entityManager
            ->getRepository($entityClass)
            ->findOneBy(
                [$fieldName => $token]
            );
    }
}