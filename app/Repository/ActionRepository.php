<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Action;
use App\Exceptions\ActionNotFoundException;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * Class ActionRepository
 * @package App\Repository
 */
final class ActionRepository extends EntityRepository
{
    /**
     * @param UuidInterface $userId
     * @param UuidInterface $actionId
     * @return Action
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws ActionNotFoundException
     */
    public function findOneByUserIdAndIdOrFail(UuidInterface $userId, UuidInterface $actionId): Action {

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $query = $queryBuilder
            ->select('action, todoList')
            ->from(Action::class, 'action')
            ->join('action.todoList', 'todoList')
            ->where($queryBuilder->expr()->eq('action.id', ':actionId'))
            ->andWhere($queryBuilder->expr()->eq('todoList.user', ':userId'))
            ->setParameter('actionId', $actionId)
            ->setParameter('userId', $userId)
            ->getQuery();

        $action = $query->getOneOrNullResult();

        if (!$action) {
            throw new ActionNotFoundException();
        }

        return $action;
    }
}