<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Entity\TodoList;
use App\Exceptions\TodoListNotFoundException;
use App\Http\App\V1\Transformers\Action\ActionTransformer;
use App\Kernel\Http\Request\Request;
use App\Services\Action\CreateActionService;
use App\Services\TodoList\SearchTodoListsService;
use League\Fractal\Resource\Item;
use Ramsey\Uuid\Uuid;

/**
 * Class ActionController
 * @package App\Http\App\V1\Controller
 */
final class ActionController extends Controller
{
    /**
     * @var array
     */
    protected $shouldBeAuthorized = [
        'create',
        'update',
        'delete',
    ];

    /**
     * @var Request
     */
    private $request;

    /**
     * @var CreateActionService
     */
    private $createActionService;

    /**
     * @var SearchTodoListsService
     */
    private $searchTodoListsService;

    /**
     * @param Request $request
     * @param CreateActionService $createActionService
     * @param SearchTodoListsService $searchTodoListsService
     */
    public function __construct(
        Request $request,
        CreateActionService $createActionService,
        SearchTodoListsService $searchTodoListsService
    ) {
        $this->request = $request;
        $this->createActionService = $createActionService;
        $this->searchTodoListsService = $searchTodoListsService;
    }

    /**
     * @param string $id
     * @return Item
     * @throws TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     */
    public function create(string $id): Item
    {
        $action = $this->createActionService->create(
            $this->request->post('title'),
            $this->getTodoListById($id)
        );

        return new Item($action, new ActionTransformer(), 'action');
    }

    public function update(): Item
    {

    }

    public function delete(): array
    {

    }

    /**
     * @param string $id
     * @return TodoList|object
     * @throws TodoListNotFoundException
     */
    private function getTodoListById(string $id): TodoList
    {
        try {
            $uuid = Uuid::fromString($id);
        } catch (\Throwable $exception) {
            throw new TodoListNotFoundException('Bad uuid');
        }

        $todoList = $this->searchTodoListsService->searchByIdAndUserId(
            $uuid,
            $this->request->getUser()->getId()
        );

        return $todoList;
    }
}