<?php


namespace MyApp\Tests\Models\Authentication;


use MyApp\App;
use MyApp\Models\Authentication\Entities\User;
use MyApp\Models\Authentication\Repositories\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    /** @var App */
    protected static $app;

    public static function setUpBeforeClass()
    {
        self::$app = new App();
    }

    /**
     * @param $containerKey
     *
     * @return mixed
     */
    private function get($containerKey)
    {
        return self::$app->getContainer()->get($containerKey);
    }

    public function testStore()
    {
        $user = new User(
            'test@example.com',
            '123',
            'Hello',
            'FooBar'
        );

        /** @var $userRepository UserRepository */
        $userRepository = $this->get(UserRepository::class);

        $userRepository->storeUser($user, false);

        //Make sure we get a Uuid
        $this->assertNotNull($user->getId());

        //Make sure we can get the User Class Back
        $foundUser = $userRepository->find($user->getId());
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->getEmail(), $foundUser->getEmail());
        $this->assertEquals($user->getFirstName(), $foundUser->getFirstName());
        $this->assertEquals($user->getLastName(), $foundUser->getLastName());
        $this->assertTrue($foundUser->isPasswordSame('123'));
    }
}