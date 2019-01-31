<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Session;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * Class SessionRepository
 * @package App\Repository
 */
final class SessionRepository extends EntityRepository
{
    /**
     * @param UuidInterface $userId
     */
    public function destroySessions(UuidInterface $userId): void
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder
            ->delete(Session::class, 't')
            ->where(
                $queryBuilder->expr()->eq('t.user', ':userId')
            )
            ->setParameter('userId', $userId);

        $queryBuilder->getQuery()->execute();
    }
}