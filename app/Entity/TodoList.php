<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

/**
 * @Table(name="todo_lists")
 * @Entity(repositoryClass="App\Repository\TodoListRepository")
 *
 * Class TodoList
 * @package App\Entity
 */
class TodoList
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
     * @Column(type="string", length=50)
     *
     * @var string
     */
    private $title;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="todoLists")
     * @JoinColumn(nullable=false)
     *
     * @var User
     **/
    private $user;

    /**
     * @OneToMany(targetEntity="Action", mappedBy="todoList")
     *
     * @var Action[]|Collection
     **/
    private $actions;

    /**
     * @Column(type="datetime")
     *
     * @var DateTime
     */
    private $createdAt;

    /**
     * @Column(type="datetime")
     *
     * @var DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
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
     * @param Action $action
     */
    public function addAction(Action $action): void
    {
        $this->actions[] = $action;
    }

    /**
     * @return Action[]|Collection
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}