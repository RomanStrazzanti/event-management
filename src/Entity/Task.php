<?php
// src/Entity/Task.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TaskRepository;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $event;

    #[ORM\Column(type: "string", length: 255)]
    private $taskName;

    #[ORM\Column(type: "date")]
    private $deadline;

    #[ORM\ManyToOne(targetEntity: Vendor::class)]
    #[ORM\JoinColumn(name: "assigned_to", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private $assignedTo;

    #[ORM\Column(type: "string", length: 20)]
    private $status;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function getTaskName(): ?string
    {
        return $this->taskName;
    }

    public function setTaskName(string $taskName): self
    {
        $this->taskName = $taskName;
        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;
        return $this;
    }

    public function getAssignedTo(): ?Vendor
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(?Vendor $assignedTo): self
    {
        $this->assignedTo = $assignedTo;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}
