<?php declare(strict_types=1);

namespace App\Kernel\Utils\Validation\Rules;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Rakit\Validation\Rule;

/**
 * Class UniqueRule
 * @package App\Kernel\Utils\Validation\Rules
 */
final class UniqueRule extends Rule
{
    /**
     * @var string
     */
    protected $message = ':attribute :value has been already used';

    /**
     * @var array
     */
    protected $fillableParams = ['table', 'column', 'except'];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $value
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function check($value): bool
    {
        $this->requireParameters(['table', 'column']);

        $table = $this->parameter('table');
        $column = $this->parameter('column');
        $except = $this->parameter('except');

        if ($except && $except === $value) {
            return true;
        }

        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('count', 'count');

        $sql = 'SELECT COUNT(*) AS count FROM ' . $table . ' t WHERE t. ' . $column . ' = :value';

        $query = $this->entityManager
            ->createNativeQuery($sql, $rsm)
            ->setParameter('value', $value);

        $result = (int)$query->getSingleScalarResult();

        return $result === 0;
    }
}