<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\EventPartner;
use App\Entity\Client;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: "events")]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $location = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $type = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $totalPrice = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventPartner::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $eventPartners;

    public function __construct()
    {
        $this->eventPartners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

    /**
     * @return Collection<int, EventPartner>
     */
    public function getEventPartners(): Collection
    {
        return $this->eventPartners;
    }

    public function addEventPartner(EventPartner $eventPartner): static
    {
        if (!$this->eventPartners->contains($eventPartner)) {
            $this->eventPartners[] = $eventPartner;
            $eventPartner->setEvent($this);
        }

        return $this;
    }

    public function removeEventPartner(EventPartner $eventPartner): static
    {
        if ($this->eventPartners->removeElement($eventPartner)) {
            // set the owning side to null (unless already changed)
            if ($eventPartner->getEvent() === $this) {
                $eventPartner->setEvent(null);
            }
        }

        return $this;
    }
}
