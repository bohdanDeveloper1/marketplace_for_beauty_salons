<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\OneToMany(mappedBy: 'belongToCity', targetEntity: Salon::class)]
    private Collection $salons;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coordinatesLatitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coordinatesLongtitude = null;

    public function __construct()
    {
        $this->salons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

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

    /**
     * @return Collection<int, Salon>
     */
    public function getSalons(): Collection
    {
        return $this->salons;
    }

    public function addSalon(Salon $salon): static
    {
        if (!$this->salons->contains($salon)) {
            $this->salons->add($salon);
            $salon->setBelongToCity($this);
        }

        return $this;
    }

    public function removeSalon(Salon $salon): static
    {
        if ($this->salons->removeElement($salon)) {
            // set the owning side to null (unless already changed)
            if ($salon->getBelongToCity() === $this) {
                $salon->setBelongToCity(null);
            }
        }

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
