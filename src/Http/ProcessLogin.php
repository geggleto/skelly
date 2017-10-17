<?php


namespace MyApp\Http;


use MyApp\Models\Authentication\Entities\User;
use MyApp\Models\Authentication\Repositories\UserRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class ProcessLogin
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request, Response $response)
    {
        $username = $request->getParsedBodyParam('username');
        $password = $request->getParsedBodyParam('password');

        $user = $this->repository->findByEmail($username);
        if ($user instanceof User && $user->isPasswordSame($password)) {
            $_SESSION['user'] = $user->toArray();

            return $response->withRedirect('/dashboard');
        }

        return $response->withStatus(404);
    }
}