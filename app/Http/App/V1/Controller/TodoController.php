<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Exceptions\TodoListNotFoundException;
use App\Http\App\V1\Transformers\TodoList\TodoListBriefTransformer;
use App\Http\App\V1\Transformers\TodoList\TodoListTransformer;
use App\Kernel\Http\Request\Request;
use App\Services\TodoList\ClearCompletedActionsService;
use App\Services\TodoList\CreateTodoListService;
use App\Services\TodoList\DeleteTodoListService;
use App\Services\TodoList\GetTodoListService;
use App\Services\TodoList\SearchTodoListsService;
use App\Services\TodoList\UpdateTodoListService;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Ramsey\Uuid\Uuid;

/**
 * Class TodoController
 * @package App\Http\App\V1\Controller
 */
final class TodoController extends Controller
{
    /**
     * @var array
     */
    protected $shouldBeAuthorized = [
        'create',
        'update',
        'getTodoLists',
        'getTodoList',
        'delete',
        'deleteCompletedActions',
    ];
    /**
     * @var Request
     */
    private $request;
    /**
     * @var CreateTodoListService
     */
    private $createTodoListService;

    /**
     * @var SearchTodoListsService
     */
    private $searchTodoListsService;

    /**
     * @var UpdateTodoListService
     */
    private $updateTodoListService;

    /**
     * @var DeleteTodoListService
     */
    private $deleteTodoListService;
    /**
     * @var GetTodoListService
     */
    private $getTodoListService;

    /**
     * @var ClearCompletedActionsService
     */
    private $clearCompletedActionsService;

    /**
     * @param Request $request
     * @param CreateTodoListService $createTodoListService
     * @param GetTodoListService $getTodoListService
     * @param SearchTodoListsService $searchTodoListsService
     * @param UpdateTodoListService $updateTodoListService
     * @param DeleteTodoListService $deleteTodoListService
     * @param ClearCompletedActionsService $clearCompletedActionsService
     */
    public function __construct(
        Request $request,
        CreateTodoListService $createTodoListService,
        GetTodoListService $getTodoListService,
        SearchTodoListsService $searchTodoListsService,
        UpdateTodoListService $updateTodoListService,
        DeleteTodoListService $deleteTodoListService,
        ClearCompletedActionsService $clearCompletedActionsService
    ) {
        $this->request = $request;
        $this->createTodoListService = $createTodoListService;
        $this->searchTodoListsService = $searchTodoListsService;
        $this->updateTodoListService = $updateTodoListService;
        $this->deleteTodoListService = $deleteTodoListService;
        $this->getTodoListService = $getTodoListService;
        $this->clearCompletedActionsService = $clearCompletedActionsService;
    }

    /**
     * @return Item
     * @throws \App\Exceptions\ValidationException
     */
    public function create(): Item
    {
        $todoList = $this->createTodoListService->create(
            $this->request->post('title'),
            $this->request->getUser()
        );

        return new Item($todoList, new TodoListBriefTransformer(), 'todoList');
    }

    /**
     * @param string $id
     * @return Item
     * @throws TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     */
    public function update(string $id): Item
    {
        $todoList = $this->updateTodoListService->update(
            $this->request->getUser(),
            Uuid::fromString($id),
            $this->request->post('title')
        );

        return new Item($todoList, new TodoListTransformer(), 'todoList');
    }

    /**
     * @return Collection
     */
    public function getTodoLists(): Collection
    {
        $todoLists = $this->request->getUser()->getTodoLists();

        return new Collection($todoLists, new TodoListBriefTransformer(), 'todoLists');
    }

    /**
     * @param string $id
     * @return Item
     * @throws \App\Exceptions\TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTodoList(string $id): Item
    {
        $todoList = $this->getTodoListService->getWithActions(
            $this->request->getUser(),
            Uuid::fromString($id),
            $this->request->get('completed')
        );

        return new Item($todoList, new TodoListTransformer(), 'todoList');
    }

    /**
     * @param string $id
     * @return array
     * @throws TodoListNotFoundException
     */
    public function delete(string $id): array
    {
        $todoList = $this->searchTodoListsService->findByIdAndUserIdOrFail(
            Uuid::fromString($id),
            $this->request->getUser()->getId()
        );

        $this->deleteTodoListService->delete($todoList);

        return [];
    }

    /**
     * @param string $todoListId
     * @return Item
     * @throws TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function deleteCompletedActions(string $todoListId): Item
    {
        $cleanedTodoList = $this->clearCompletedActionsService->clear(
            $this->request->getUser(), Uuid::fromString($todoListId)
        );

        return new Item($cleanedTodoList, new TodoListTransformer(), 'todoList');
    }
}