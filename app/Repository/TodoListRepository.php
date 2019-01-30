<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TodoList;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Ramsey\Uuid\UuidInterface;

/**
 * Class TodoListRepository
 * @package App\Repository
 */
final class TodoListRepository extends EntityRepository
{
    /**
     * @param UuidInterface $id
     * @param UuidInterface $userId
     * @param bool|null $completed
     * @return TodoList
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function searchByUserAndCompletedState(
        UuidInterface $id,
        UuidInterface $userId,
        bool $completed = null
    ): ? TodoList
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder
            ->select('todoList, actions')
            ->from(TodoList::class, 'todoList')
            ->where($queryBuilder->expr()->eq('todoList.id', ':todoListId'))
            ->andWhere($queryBuilder->expr()->eq('todoList.user', ':userId'))
            ->setParameter('todoListId', $id)
            ->setParameter('userId', $userId);

        if (null === $completed) {
            $queryBuilder
                ->leftJoin('todoList.actions', 'actions');
        } else {
            $queryBuilder
                ->leftJoin(
                    'todoList.actions',
                    'actions',
                    Join::WITH,
                    $queryBuilder->expr()->eq('actions.completed', ':completed'))
                ->setParameter('completed', $completed);
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}