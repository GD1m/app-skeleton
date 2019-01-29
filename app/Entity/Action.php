<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Ramsey\Uuid\UuidInterface;

/**
 * @Entity
 * @Table(name="actions")
 *
 * Class Action
 * @package App\Entity
 */
class Action
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
     * @Column(type="string", length=50)
     *
     * @var string
     */
    private $title;

    /**
     * @ManyToOne(targetEntity="TodoList", inversedBy="actions")
     * @JoinColumn(nullable=false)
     *
     * @var TodoList
     **/
    private $todoList;

    /**
     * @Column(type="boolean")
     *
     * @var bool
     */
    private $completed;

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
     * @return TodoList
     */
    public function getTodoList(): TodoList
    {
        return $this->todoList;
    }

    /**
     * @param TodoList $todoList
     */
    public function setTodoList(TodoList $todoList): void
    {
        $this->todoList = $todoList;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
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