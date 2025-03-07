<?php
// src/Entity/Event.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    #[ORM\Column(type: "string", length: 255)]
    private $eventName;

    #[ORM\Column(type: "date")]
    private $date;

    #[ORM\Column(type: "string", length: 255)]
    private $location;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private $budget;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): self
    {
        $this->eventName = $eventName;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getBudget(): ?string
    {
        return $this->budget;
    }

    public function setBudget(string $budget): self
    {
        $this->budget = $budget;
        return $this;
    }
}
