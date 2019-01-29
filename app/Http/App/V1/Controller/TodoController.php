<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Kernel\Http\Request\Request;
use App\Services\TodoList\CreateTodoListService;

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
     * @param Request $request
     * @param CreateTodoListService $createTodoListService
     */
    public function __construct(Request $request, CreateTodoListService $createTodoListService)
    {
        $this->request = $request;
        $this->createTodoListService = $createTodoListService;
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
}