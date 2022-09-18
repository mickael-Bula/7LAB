<?php

namespace App\DataFixtures;

use App\Entity\Constructeur;
use App\Entity\Voiture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    // liste des constructeurs
    public $constructors = [
        ['Renault', 'France', ['Clio', 'Mégane', 'Scénic']],
        ['Peugeot', 'France', ['308', '3008', '2008']],
        ['Dacia', 'France', ['Sandero', 'Duster']],
        ['BMW', 'Allemagne', ['X1', 'X3', 'M4']],
        ['Mercedes', 'Allemagne', ['class A', 'class B', 'class C']],
        ['Skoda', 'République Tchèque', ['Octavia', 'Superb', 'Fabia']],
    ];

    // liste des carburants
    public $fuels = [
        'sans plomb',
        'diesel',
        'électrique',
    ];

    public $vehicles = [];
    public $fixtureBrands = [];

    public function getConstructors()
    {
        // création des constructeurs
        foreach ($this->constructors as $constructor) {
            $constructeur = new Constructeur();
            $constructeur->setName($constructor[0]);
            $constructeur->setCountry($constructor[1]);
            $site = 'https://' . strtolower($constructor[0]) . '.com';
            $constructeur->setSite($site);

            $this->fixtureBrands[] = $constructeur;
        }
    }

    public function getVehicles()
    {
        // création de 10 véhicules
        for ($i=0; $i < 10; $i++) {
            // récupération d'une marque et d'un modèle
            [$constructor, $model] = $this->getModelFromArray($this->constructors);

            // création de l'objet voiture
            $voiture = new Voiture();
            $voiture->setModel($model);

            // récupération d'un objet Constructeur à partir de son nom dans le tableau $fixtureBrands
            $brand = $this->getConstructorObjectFromName($this->fixtureBrands, $constructor);
            $voiture->setConstructor($brand);
            $voiture->setEnergy($this->fuels[array_rand($this->fuels)]);
            $voiture->setSeat(mt_rand(4, 5));
            $voiture->setLength(mt_rand(350, 480) / 100);
            $voiture->setWidth(mt_rand(160, 190) / 100);
            $voiture->setWeight(mt_rand(1250, 1500));

            $this->vehicles[] = $voiture;
        }
    }

    /** Méthode retournant aléatoirement un constructeur */
    function getModelFromArray(array $array): array
    {
        // on récupère aléatoirement un constructeur
        $constructor = $array[array_rand($array)];

        // on récupère le nom d'un modèle du constructeur au hasard
        $model = $constructor[2][array_rand($constructor[2])];

        // on retourne un modèle et le nom de son constructeur
        return [$constructor[0], $model];
    }

    /**
     * @param Constructeur[]
     * @param string $name
     * @return Constructeur|void
     */
    public function getConstructorObjectFromName(array $constructorList, string $name): Constructeur
    {
        $result = null;
        foreach ($constructorList as $constructor) {
            if ($constructor->getName() === $name) {
                $result = $constructor;
                break;
            }
        }
        return $result;
    }

    public function load(ObjectManager $manager): void
    {
        // création des constructeurs
        $this->getConstructors();

        // création des véhicules
        $this->getVehicles();

        // enregistrement des constructeurs en mémoire
        foreach ($this->fixtureBrands as $constructor) {
            $manager->persist($constructor);
        }

        // enregistrement des véhicules en mémoire
        foreach ($this->vehicles as $vehicle) {
            $manager->persist($vehicle);
        }

        // // enregistrement en BDD
        $manager->flush();
    }
}
