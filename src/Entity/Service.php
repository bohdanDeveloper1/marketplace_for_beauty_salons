<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Stylist::class)]
    private Collection $stylists;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Salon $salon = null;
    public function __construct()
    {
        $this->stylists = new ArrayCollection();
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

    /**
     * @return Collection<int, Stylist>
     */
    public function getStylists(): Collection
    {
        return $this->stylists;
    }

    public function addStylist(Stylist $stylist): static
    {
        if (!$this->stylists->contains($stylist)) {
            $this->stylists->add($stylist);
            $stylist->setCategory($this);
        }

        return $this;
    }

    public function removeStylist(Stylist $stylist): static
    {
        if ($this->stylists->removeElement($stylist)) {
            // set the owning side to null (unless already changed)
            if ($stylist->getCategory() === $this) {
                $stylist->setCategory(null);
            }
        }

        return $this;
    }

    public function getSalon(): ?Salon
    {
        return $this->salon;
    }

    public function setSalon(?Salon $salon): static
    {
        $this->salon = $salon;

        return $this;
    }
}
