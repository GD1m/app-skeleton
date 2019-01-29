<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

/**
 * Class TodoListRepository
 * @package App\Repository
 */
final class TodoListRepository extends EntityRepository
{
    /**
     * @param User $user
     * @param bool|null $completed
     * @return Collection
     */
    public function searchByUserAndCompletedState(User $user, bool $completed = null): Collection
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();

        $criteria->where(
            $expr->eq('user', $user->getId())
        );

        if (null !== $completed) {
            $criteria->andWhere(
                $expr->eq('completed', $completed)
            );
        }

        return $this->matching($criteria);
    }
}