<?php

namespace App\Entity;

use App\Repository\SalonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalonRepository::class)]
class Salon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'salon', targetEntity: Service::class)]
    private Collection $services;

    #[ORM\ManyToOne(inversedBy: 'salons2')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $salonOwner = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'salons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $belongToCity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coordinatesLatitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coordinatesLongtitude = null;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setSalon($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getSalon() === $this) {
                $service->setSalon(null);
            }
        }

        return $this;
    }

    public function getSalonOwner(): ?User
    {
        return $this->salonOwner;
    }

    public function setSalonOwner(?User $salonOwner): static
    {
        $this->salonOwner = $salonOwner;

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

    public function getBelongToCity(): ?City
    {
        return $this->belongToCity;
    }

    public function setBelongToCity(?City $belongToCity): static
    {
        $this->belongToCity = $belongToCity;

        return $this;
    }

    public function getCoordinatesLatitude(): ?string
    {
        return $this->coordinatesLatitude;
    }

    public function setCoordinatesLatitude(?string $coordinatesLatitude): static
    {
        $this->coordinatesLatitude = $coordinatesLatitude;

        return $this;
    }

    public function getCoordinatesLongtitude(): ?string
    {
        return $this->coordinatesLongtitude;
    }

    public function setCoordinatesLongtitude(?string $coordinatesLongtitude): static
    {
        $this->coordinatesLongtitude = $coordinatesLongtitude;

        return $this;
    }
}
