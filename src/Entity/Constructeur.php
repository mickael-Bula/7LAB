<?php

namespace App\Entity;

use App\Repository\ConstructeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConstructeurRepository::class)
 */
class Constructeur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $site;

    /**
     * @ORM\OneToMany(targetEntity=Voiture::class, mappedBy="constructor")
     */
    private $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Voiture $car): self
    {
        if (!$this->cars->contains($car)) {
            $this->cars[] = $car;
            $car->setConstructor($this);
        }

        return $this;
    }

    public function removeCar(Voiture $car): self
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getConstructor() === $this) {
                $car->setConstructor(null);
            }
        }

        return $this;
    }
}
