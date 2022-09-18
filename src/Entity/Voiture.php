<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VoitureRepository::class)
 */
class Voiture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("car-edit")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Veuillez saisir un modèle pour le véhicule")
     * @Groups("car-edit")
     */
    private $model;

    /**
     * @ORM\Column(type="float")
     * @Assert\Type(type="float", message = "La valeur {{ value }} doit être de type {{ type }}")
     * @Assert\NotBlank(message="Veuillez saisir la longueur du véhicule")
     * @Groups("car-edit")
     */
    private $length;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veuillez saisir une largeur pour le véhicule")
     * @Groups("car-edit")
     */
    private $width;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veuillez renseigner le poids du véhicule")
     * @Groups("car-edit")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer", message = "La valeur {{ value }} doit être de type {{ type }}")
     * @Assert\NotBlank(message="Veuillez le nombre de places assises")
     * @Groups("car-edit")
     */
    private $seat;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("car-edit")
     */
    private $energy;

    /**
     * @ORM\ManyToOne(targetEntity=Constructeur::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("car-edit")
     */
    private $constructor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    /*  J'ajoute un ? au type hinting pour gérer le cas d'un champ vide lors de l'édition d'un véhicule
        Avec cette modification les contrôles de setters sont passés et l'on peut faire travailler les validators ( @Assert)
     */
    public function setLength(?float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSeat(): ?int
    {
        return $this->seat;
    }

    public function setSeat(int $seat): self
    {
        $this->seat = $seat;

        return $this;
    }

    public function getEnergy(): ?string
    {
        return $this->energy;
    }

    public function setEnergy(string $energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    public function getConstructor(): ?Constructeur
    {
        return $this->constructor;
    }

    public function setConstructor(?Constructeur $constructor): self
    {
        $this->constructor = $constructor;

        return $this;
    }
}
