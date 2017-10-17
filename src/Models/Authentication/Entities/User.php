<?php

namespace MyApp\Models\Authentication\Entities;

use function bin2hex;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use function password_verify;
use function random_bytes;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @Id
     * @Column(type="uuid", unique=true)
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     * @Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     * @Column(type="string")
     */
    private $password;

    /**
     * @var string
     * @Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     * @Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string
     * @Column(type="string", unique=true, nullable=true)
     */
    private $token;

    /**
     * User constructor.
     *
     * @param $email string
     * @param $password string
     * @param $firstName string
     * @param  $lastName string
     */
    public function __construct($email, $password, $firstName, $lastName)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;

        $this->setPassword($password);
    }

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_BCRYPT_DEFAULT_COST]);
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function isPasswordSame($password) {
        return password_verify($password, $this->password);
    }

    public function toArray()
    {
        return [
            'id' => $this->id->toString(),
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email
        ];
    }

    public function generateToken() {
        $this->token = bin2hex(random_bytes(16));

        return $this->token;
    }

    public function regeneratePassword()
    {
        $this->token = null;

        $password = bin2hex(random_bytes(16));

        $this->setPassword($password);

        return $password;
    }
}