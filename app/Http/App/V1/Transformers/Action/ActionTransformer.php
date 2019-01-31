<?php declare(strict_types=1);

namespace App\Http\App\V1\Transformers\Action;

use App\Entity\Action;
use League\Fractal\TransformerAbstract;

/**
 * Class ActionTransformer
 * @package App\Http\App\V1\Transformers\Action
 */
final class ActionTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param Action $action
     * @return array
     */
    public function transform(Action $action): array
    {
        return [
            'id' => $action->getId(),
            'title' => $action->getTitle(),
            'completed' => $action->isCompleted(),
            'createdAt' => $action->getCreatedAt()->format('c'),
            'updatedAt' => $action->getUpdatedAt()->format('c'),
        ];
    }
}