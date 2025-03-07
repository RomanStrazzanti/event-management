<?php
// src/Entity/CalendarEntry.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CalendarRepository;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
class Calendar
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $event;

    #[ORM\Column(type: "date")]
    private $date;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }
}
