<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User
 * @package App
 *
 * @Entity
 * @Table(name="users")
 */
class User
{
    /**
     * @var UuidInterface
     *
     * @Id
     * @Column(type="uuid_binary_ordered_time", unique=true)
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidOrderedTimeGenerator")
     */
    private $id;

    /**
     * @Column(type="string", unique=true)
     *
     * @var string
     */
    private $username;

    /**
     * @Column(type="string")
     *
     * @var string
     */
    private $password;

    /**
     * @Column(type="datetime")
     *
     * @var DateTime
     */
    private $created_at;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    /**
     * @param DateTime $created_at
     */
    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }
}