<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Entity\User;
use App\Http\App\V1\Transformers\User\UserTransformer;
use App\Kernel\Http\Request\Request;
use App\Services\Auth\LoginUserService;
use App\Services\Auth\RegisterUserService;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class AuthController
 * @package App\Http\App\V1\Controller
 */
final class AuthController extends Controller
{
    /**
     * @var array
     */
    protected $shouldBeAuthorized = [
        'me',
    ];

    /**
     * @var Request
     */
    private $request;
    /**
     * @var RegisterUserService
     */
    private $registerUserService;

    /**
     * @var LoginUserService
     */
    private $loginUserService;

    /**
     * @var Manager
     */
    private $fractal;

    /**
     * @param Request $request
     * @param RegisterUserService $registerUserService
     * @param LoginUserService $loginUserService
     * @param Manager $fractal
     */
    public function __construct(
        Request $request,
        RegisterUserService $registerUserService,
        LoginUserService $loginUserService,
        Manager $fractal
    ) {
        $this->request = $request;
        $this->registerUserService = $registerUserService;
        $this->loginUserService = $loginUserService;
        $this->fractal = $fractal;
    }

    /**
     * @return JsonResponse
     * @throws \App\Exceptions\ValidationException
     * @throws \App\Exceptions\InfiniteLoopException
     */
    public function register(): JsonResponse
    {
        $user = $this->registerUserService->register(
            $this->request->post('username'),
            $this->request->post('password'),
            $this->request->post('confirmPassword')
        );

        return $this->responseUserWithToken($user);
    }

    /**
     * @return JsonResponse
     * @throws \App\Exceptions\BadCredentialsException
     * @throws \App\Exceptions\InfiniteLoopException
     */
    public function login(): JsonResponse
    {
        $user = $this->loginUserService->login(
            $this->request->post('username'),
            $this->request->post('password')
        );

        return $this->responseUserWithToken($user);
    }

    /**
     * @return Item
     */
    public function me(): Item
    {
        $user = $this->request->getUser();

        return new Item($user, new UserTransformer(), 'user');
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    private function responseUserWithToken(User $user): JsonResponse
    {
        $resource = new Item($user, new UserTransformer(), 'user');

        $token = $user->getSessions()->last()->getToken();

        return (new JsonResponse(
            $this->fractal->createData($resource)->toArray()
        ))
            ->withHeader('Authorization', $token);
    }
}