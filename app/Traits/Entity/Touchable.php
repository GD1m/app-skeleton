<?php declare(strict_types=1);

namespace App\Traits\Entity;

use App\Kernel\Application;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Trait Touchable
 * @package App\Traits\Entity
 */
trait Touchable
{
    public function touch(): void
    {
        $app = Application::getInstance();

        $queryBuilder = $app->container()
            ->get(EntityManagerInterface::class)
            ->createQueryBuilder();

        $query = $queryBuilder
            ->update(__CLASS__, 't')
            ->set('t.updatedAt', ':updatedAt')
            ->where(
                $queryBuilder->expr()->eq('t.id', ':id')
            )
            ->setParameter('updatedAt', new DateTime())
            ->setParameter('id', $this->getId())
            ->getQuery();

        $query->execute();
    }
}