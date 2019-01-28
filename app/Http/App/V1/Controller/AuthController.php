<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Entity\User;
use App\Kernel\Http\Request\Request;
use App\Services\Auth\LoginUserService;
use App\Services\Auth\RegisterUserService;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class AuthController
 * @package App\Http\App\V1\Controller
 */
final class AuthController extends Controller
{
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
     * @param Request $request
     * @param RegisterUserService $registerUserService
     * @param LoginUserService $loginUserService
     */
    public function __construct(
        Request $request,
        RegisterUserService $registerUserService,
        LoginUserService $loginUserService
    ) {
        $this->request = $request;
        $this->registerUserService = $registerUserService;
        $this->loginUserService = $loginUserService;
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
     * @param User $user
     * @return JsonResponse
     */
    private function responseUserWithToken(User $user): JsonResponse
    {
        return (new JsonResponse([
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'createdAt' => $user->getCreatedAt()->format('c'),
            ],
        ]))->withHeader('Authorization', $user->getSessions()->last()->getToken());
    }
}