<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Exceptions\TodoListNotFoundException;
use App\Http\App\V1\Transformers\Action\ActionTransformer;
use App\Kernel\Http\Request\Request;
use App\Services\Action\CreateActionService;
use App\Services\Action\DeleteActionService;
use App\Services\Action\UpdateActionService;
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
     * @var UpdateActionService
     */
    private $updateActionService;

    /**
     * @var DeleteActionService
     */
    private $deleteActionService;

    /**
     * @param Request $request
     * @param CreateActionService $createActionService
     * @param SearchTodoListsService $searchTodoListsService
     * @param UpdateActionService $updateActionService
     * @param DeleteActionService $deleteActionService
     */
    public function __construct(
        Request $request,
        CreateActionService $createActionService,
        SearchTodoListsService $searchTodoListsService,
        UpdateActionService $updateActionService,
        DeleteActionService $deleteActionService
    ) {
        $this->request = $request;
        $this->createActionService = $createActionService;
        $this->searchTodoListsService = $searchTodoListsService;
        $this->updateActionService = $updateActionService;
        $this->deleteActionService = $deleteActionService;
    }

    /**
     * @param string $id
     * @return Item
     * @throws TodoListNotFoundException
     * @throws \App\Exceptions\ValidationException
     */
    public function create(string $id): Item
    {
        $todoList = $this->searchTodoListsService->findByIdAndUserIdOrFail(
            Uuid::fromString($id),
            $this->request->getUser()->getId()
        );

        $action = $this->createActionService->create(
            $this->request->post('title'),
            $todoList
        );

        return new Item($action, new ActionTransformer(), 'action');
    }

    /**
     * @param string $actionId
     * @return Item
     * @throws \App\Exceptions\ActionNotFoundException
     * @throws \App\Exceptions\ValidationException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function update(string $actionId): Item
    {
        $action = $this->updateActionService->update(
            $this->request->getUser(),
            Uuid::fromString($actionId),
            $this->request->post('title'),
            $this->request->post('completed')
        );

        return new Item($action, new ActionTransformer(), 'action');
    }

    /**
     * @param string $actionUuid
     * @return array
     * @throws \App\Exceptions\ActionNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function delete(string $actionUuid): array
    {
        $this->deleteActionService->delete($this->request->getUser(), Uuid::fromString($actionUuid));

        return [];
    }
}