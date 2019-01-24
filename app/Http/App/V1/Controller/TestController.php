<?php declare(strict_types=1);

namespace App\Http\App\V1\Controller;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Controller
 * @package App\Http\Controller
 */
class TestController extends Controller
{
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function test(): array
    {
        return [
            'id' => 1,
            'name' => 'foo',
            'host' => $this->request->getUri()->getHost(),
        ];
    }
}