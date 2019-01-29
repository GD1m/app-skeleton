<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Exceptions\TodoListNotFoundException;
use App\Http\App\V1\Transformers\TodoList\TodoListBriefTransformer;
use App\Http\App\V1\Transformers\TodoList\TodoListTransformer;
use App\Kernel\Http\Request\Request;
use App\Services\TodoList\CreateTodoListService;
use App\Services\TodoList\SearchTodoListsService;
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
        'getTodoLists',
        'getTodoList',
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
     * @param Request $request
     * @param CreateTodoListService $createTodoListService
     * @param SearchTodoListsService $searchTodoListsService
     */
    public function __construct(
        Request $request,
        CreateTodoListService $createTodoListService,
        SearchTodoListsService $searchTodoListsService
    ) {
        $this->request = $request;
        $this->createTodoListService = $createTodoListService;
        $this->searchTodoListsService = $searchTodoListsService;
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
     */
    public function getTodoList(string $id): Item
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

        return new Item($todoList, new TodoListTransformer(), 'todoList');
    }
    }
}