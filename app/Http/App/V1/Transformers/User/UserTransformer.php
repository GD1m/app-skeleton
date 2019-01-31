<?php declare(strict_types=1);

namespace App\Http\App\V1\Transformers\User;

use App\Entity\User;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer
 * @package App\Http\App\V1\Transformers\User
 */
final class UserTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'username' => $user->getUsername(),
            'createdAt' => $user->getCreatedAt()->format('c'),
        ];
    }
}