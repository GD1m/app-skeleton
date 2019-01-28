<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Session
 * @package App
 *
 * @Entity
 * @Table(name="sessions")
 * @Entity(repositoryClass="App\Repository\SessionRepository")
 */
class Session
{
    /**
     * @var UuidInterface
     *
     * @Id
     * @Column(type="uuid", unique=true)
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="sessions")
     *
     * @var User
     **/
    private $user;

    /**
     * @Column(type="string", unique=true)
     *
     * @var string
     */
    private $token;

    /**
     * @Column(type="datetime")
     *
     * @var DateTime
     */
    private $created_at;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
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