<?php


namespace MyApp\Models\Authentication\Repositories;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\TransactionRequiredException;
use Exception;
use MyApp\Core\RepositoryInterface;
use MyApp\Models\Authentication\Entities\User;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use function var_dump;

class UserRepository implements RepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Uuid $uuid
     *
     * @return null|object|User
     * @throws ORMException
     * @throws ORMInvalidArgumentException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function find(UuidInterface $uuid)
    {
        return $this->entityManager->find(User::class, $uuid->toString());
    }

    /**
     * @param Uuid $uuid
     *
     * @return bool|\Doctrine\Common\Proxy\Proxy|null|object|User
     * @throws ORMException
     */
    public function getReferenceFor(UuidInterface $uuid)
    {
        return $this->entityManager->getReference(User::class, $uuid->toString());
    }

    /**
     * @param $email
     *
     * @return User|null
     */
    public function findByEmail($email)
    {
        try {
            return $this->entityManager
                ->createQueryBuilder()
               ->select('u')
               ->from(User::class, 'u')
               ->where('u.email = :email')
               ->setParameter('email', (string)$email)
               ->getQuery()
               ->getSingleResult();
        } catch (NonUniqueResultException $exception) {
          return null;
        } catch (NoResultException $exception) {
            return null;
        }
    }

    public function storeUser(User $user, $flush = true)
    {
        $this->entityManager->persist($user);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @param $token
     *
     * @return User|null
     */
    public function findByToken($token)
    {
        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.token = :token')
                ->setParameter('token', (string)$token)
                ->getQuery()
                ->getSingleResult();
        } catch (NonUniqueResultException $exception) {
            return null;
        } catch (NoResultException $exception) {
            return null;
        }
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return User[]
     */
    public function getUserList($page = 0, $limit = 20)
    {
        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('u')
                ->from(User::class, 'u')
                ->orderBy('u.email', 'desc')
                ->setMaxResults($limit)
                ->setFirstResult($page*$limit)
                ->getQuery()
                ->getResult();

        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @param int $limit
     *
     * @return int
     */
    public function getMaxPage($limit = 20)
    {
        try {
            $count = $this->entityManager
                ->createQueryBuilder()
                ->select('count(*) as `count')
                ->from(User::class, 'u')
                ->getQuery()
                ->getSingleResult();

            return (int)($count/$limit);

        } catch (Exception $exception) {
            return 0;
        }
    }
}