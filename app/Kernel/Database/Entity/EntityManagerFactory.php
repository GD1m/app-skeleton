<?php declare(strict_types=1);

namespace App\Kernel\Database\Entity;

use App\Kernel\Application;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\Doctrine\UuidType;

/**
 * Class EntityManagerFactory
 * @package App\Kernel\Database\Entity
 */
final class EntityManagerFactory
{
    /**
     * @param ContainerInterface $container
     * @return EntityManagerInterface
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public function __invoke(ContainerInterface $container): EntityManagerInterface
    {
        $isDevMode = true; // TODO extract it

        $basePath = $container->get(Application::class)->basePath();

        $config = Setup::createAnnotationMetadataConfiguration([$basePath . 'app'], $isDevMode);

        $connection = require $basePath . 'config/database.php';

        $entityManager = EntityManager::create($connection, $config);

        $this->bindDoctrineTypes($entityManager);

        return $entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @throws \Doctrine\DBAL\DBALException
     */
    private function bindDoctrineTypes(EntityManagerInterface $entityManager): void
    {
        Type::addType('uuid', UuidType::class);
    }
}