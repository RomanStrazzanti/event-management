<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventPartnerRepository;

#[ORM\Entity(repositoryClass: EventPartnerRepository::class)]
#[ORM\Table(name: "event_partners")]
class EventPartner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    #[ORM\ManyToOne(targetEntity: Partner::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Partner $partner = null;

    #[ORM\ManyToOne(targetEntity: Forfait::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Forfait $forfait = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?float $amountPaid = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $paymentDate = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $notes = null;

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

    public function getPartner(): ?Partner
    {
        return $this->partner;
    }

    public function setPartner(?Partner $partner): self
    {
        $this->partner = $partner;
        return $this;
    }

    public function getForfait(): ?Forfait
    {
        return $this->forfait;
    }

    public function setForfait(?Forfait $forfait): self
    {
        $this->forfait = $forfait;
        if ($forfait) {
            // Calculer le montant payé en fonction du prix du forfait
            $this->calculateAmountPaid($forfait);
        }
        return $this;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amountPaid;
    }

    public function setAmountPaid(float $amountPaid): self
    {
        $this->amountPaid = $amountPaid;
        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?\DateTimeInterface $paymentDate): self
    {
        $this->paymentDate = $paymentDate;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }
    
    private function calculateAmountPaid(Forfait $forfait): void
    {
        // Récupère le prix du forfait
        $price = (float) $forfait->getPrice();
        
        // Applique une marge de 15% (modifiable selon ton besoin)
        $marginRate = 0.15; 
        $this->amountPaid = round($price * (1 + $marginRate), 2);
    }
}
