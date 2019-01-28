<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use App\Kernel\Http\Request\Request;
use App\Services\Auth\RegisterUserService;

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
     * @param Request $request
     * @param RegisterUserService $registerUserService
     */
    public function __construct(Request $request, RegisterUserService $registerUserService)
    {
        $this->request = $request;
        $this->registerUserService = $registerUserService;
    }

    /**
     * @return array
     * @throws \App\Exceptions\ValidationException
     */
    public function register(): array
    {
        $user = $this->registerUserService->register(
            $this->request->post('username'),
            $this->request->post('password'),
            $this->request->post('confirmPassword')
        );

        return [
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'createdAt' => $user->getCreatedAt()->format('c'),
            ],
        ];
    }
}