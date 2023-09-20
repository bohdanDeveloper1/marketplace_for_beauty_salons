<?php

namespace App\Entity;

use App\Repository\StylistWorksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StylistWorksRepository::class)]
class StylistWorks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $service_name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\OneToMany(mappedBy: 'stylistWork', targetEntity: Shedule::class)]
    private Collection $registeredShedules;

    #[ORM\ManyToOne(inversedBy: 'stylistWorks4')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stylist $stylist = null;

    public function __construct()
    {
        $this->registeredShedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceName(): ?string
    {
        return $this->service_name;
    }

    public function setServiceName(string $service_name): static
    {
        $this->service_name = $service_name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
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

    /**
     * @return Collection<int, Shedule>
     */
    public function getRegisteredShedules(): Collection
    {
        return $this->registeredShedules;
    }

    public function addRegisteredShedule(Shedule $registeredShedule): static
    {
        if (!$this->registeredShedules->contains($registeredShedule)) {
            $this->registeredShedules->add($registeredShedule);
            $registeredShedule->setStylistWork($this);
        }

        return $this;
    }

    public function removeRegisteredShedule(Shedule $registeredShedule): static
    {
        if ($this->registeredShedules->removeElement($registeredShedule)) {
            // set the owning side to null (unless already changed)
            if ($registeredShedule->getStylistWork() === $this) {
                $registeredShedule->setStylistWork(null);
            }
        }

        return $this;
    }

    public function getStylist(): ?Stylist
    {
        return $this->stylist;
    }

    public function setStylist(?Stylist $stylist): static
    {
        $this->stylist = $stylist;

        return $this;
    }
}
