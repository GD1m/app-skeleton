<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

/**
 * @Table(name="users")
 * @Entity
 *
 * Class User
 * @package App
 */
class User
{
    /**
     * @Id
     * @Column(type="uuid", unique=true)
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var UuidInterface
     */
    private $id;

    /**
     * @Column(type="string", unique=true, length=50)
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

    /**
     * @OneToMany(targetEntity="Session", mappedBy="user")
     *
     * @var Session[]|Collection
     **/
    private $sessions;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
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

    /**
     * @param Session $session
     */
    public function addSession(Session $session): void
    {
        $this->sessions[] = $session;
    }

    /**
     * @return Session[]|Collection
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }
}