<?php


namespace MyApp\Models\Authentication\Services;


use MyApp\Models\Authentication\Repositories\UserRepository;
use Ramsey\Uuid\UuidInterface;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function find(UuidInterface $uuid)
    {
        return $this->userRepository->find($uuid);
    }

    public function getUsers($page = 0, $limit = 20)
    {
        return $this->userRepository->getUserList($page, $limit);
    }

    public function getMaxPage($limit = 20)
    {
        return $this->userRepository->getMaxPage($limit);
    }

}