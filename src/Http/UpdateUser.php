<?php


namespace MyApp\Http;


use MyApp\Models\Authentication\Repositories\UserRepository;
use Ramsey\Uuid\Uuid;
use Slim\Http\Request;
use Slim\Http\Response;

class UpdateUser
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request, Response $response)
    {
        $user = $request->getParsedBody();

        $userEntity = $this->userRepository->find(
            Uuid::fromString($user['id'])
        );

        $userEntity->setEmail($user['email']);
        $userEntity->setFirstName($user['firstName']);
        $userEntity->setLastName($user['lastName']);

        if ($user['password'] === $user['password2'] && !empty($user['password'])) {
            $userEntity->setPassword($user['password']);
        }

        $this->userRepository->storeUser($userEntity);

        $_SESSION['user'] = $userEntity->toArray();

        return $response->withJson(['message' => 'Success']);
    }
}