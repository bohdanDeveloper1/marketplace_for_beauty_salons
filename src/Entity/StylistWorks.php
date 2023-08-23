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

    #[ORM\ManyToOne(inversedBy: 'stylistWorks3')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stylist $stylist = null;

    #[ORM\Column]
    private ?int $time = null;

    #[ORM\OneToMany(mappedBy: 'stylist', targetEntity: Shedule::class)]
    private Collection $shedules;

    public function __construct()
    {
        $this->shedules = new ArrayCollection();
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

    public function getStylist(): ?Stylist
    {
        return $this->stylist;
    }

    public function setStylist(?Stylist $stylist): static
    {
        $this->stylist = $stylist;

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
    public function getShedules(): Collection
    {
        return $this->shedules;
    }

    public function addShedule(Shedule $shedule): static
    {
        if (!$this->shedules->contains($shedule)) {
            $this->shedules->add($shedule);
            $shedule->setStylist($this);
        }

        return $this;
    }

    public function removeShedule(Shedule $shedule): static
    {
        if ($this->shedules->removeElement($shedule)) {
            // set the owning side to null (unless already changed)
            if ($shedule->getStylist() === $this) {
                $shedule->setStylist(null);
            }
        }

        return $this;
    }
}
