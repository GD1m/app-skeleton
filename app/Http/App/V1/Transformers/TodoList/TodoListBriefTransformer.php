<?php declare(strict_types=1);

namespace App\Http\App\V1\Transformers\TodoList;

use App\Entity\TodoList;
use App\Http\App\V1\Transformers\Action\ActionTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class TodoListBriefTransformer
 * @package App\Http\App\V1\Transformers\TodoList
 */
final class TodoListBriefTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param TodoList $todoList
     * @return array
     */
    public function transform(TodoList $todoList): array
    {
        return [
            'id' => $todoList->getId(),
            'title' => $todoList->getTitle(),
            'createdAt' => $todoList->getCreatedAt()->format('c'),
            'updatedAt' => $todoList->getUpdatedAt()->format('c'),
        ];
    }
}