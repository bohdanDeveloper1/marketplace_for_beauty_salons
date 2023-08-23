<?php

namespace App\Entity;

use App\Repository\SheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SheduleRepository::class)]
class Shedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $day = null;

    #[ORM\ManyToOne(inversedBy: 'shedules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StylistWorks $stylist = null;

    #[ORM\Column(length: 255)]
    private ?string $serviceName = null;

    #[ORM\Column]
    private ?float $servicePrice = null;

    #[ORM\Column(length: 255)]
    private ?string $userEmail = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $visitTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getStylist(): ?StylistWorks
    {
        return $this->stylist;
    }

    public function setStylist(?StylistWorks $stylist): static
    {
        $this->stylist = $stylist;

        return $this;
    }

    public function getServiceName(): ?string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): static
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function getServicePrice(): ?float
    {
        return $this->servicePrice;
    }

    public function setServicePrice(float $servicePrice): static
    {
        $this->servicePrice = $servicePrice;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getVisitTime(): ?\DateTimeInterface
    {
        return $this->visitTime;
    }

    public function setVisitTime(\DateTimeInterface $visitTime): static
    {
        $this->visitTime = $visitTime;

        return $this;
    }
}
