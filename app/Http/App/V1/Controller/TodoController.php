<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Entity\TodoList;
use App\Exceptions\TodoListNotFoundException;
use App\Kernel\Http\Request\Request;
use App\Services\TodoList\CreateTodoListService;
use App\Services\TodoList\SearchTodoListsService;
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
     * @return array
     * @throws \App\Exceptions\ValidationException
     */
    public function create(): array
    {
        $todoList = $this->createTodoListService->create(
            $this->request->post('title'),
            $this->request->getUser()
        );

        return [
            'todoList' => [
                'id' => $todoList->getId(),
                'title' => $todoList->getTitle(),
                'actions' => $todoList->getActions()->toArray(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getTodoLists(): array
    {
        $todoLists = $this->request->getUser()->getTodoLists();

        return [
            'todoLists' => array_map(function (TodoList $todoList) {
                return [
                    'id' => $todoList->getId(),
                    'title' => $todoList->getTitle(),
                    'createdAt' => $todoList->getCreatedAt()->format('c'),
                    'updatedAt' => $todoList->getUpdatedAt()->format('c'),
                ];
            }, $todoLists->toArray())
        ];
    }

    /**
     * @param string $id
     * @return array
     * @throws \App\Exceptions\TodoListNotFoundException
     */
    public function getTodoList(string $id): array
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

        return [
            'todoList' => [
                'id' => $todoList->getId(),
                'title' => $todoList->getTitle(),
                'actions' => $todoList->getActions()->toArray(),
            ],
        ];
    }
}