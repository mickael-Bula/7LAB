<?php

namespace App\DataFixtures;

use App\Entity\Voiture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = (new \Faker\Factory())::create();
        $faker->addProvider(new \Faker\Provider\Fakecar($faker));

        // liste de constructeurs
        $brands = [
            'Renault',
            'Peugeot',
            'Dacia',
            'BMW',
        ];

        // liste des carburants
        $fuels = [
            'sans plomb',
            'diesel',
            'électrique',
        ];

        /** Méthode personnalisée retournant aléatoirement un modèle et son constructeur */
        function getConstructorFromArray($brands): array
        {
            $brand = array_rand($brands);   // retourne une clé au hasard (eg. BMW)
            $models = $brands[$brand];      // retourne le tableau qui se trouve e valeur de la clé précédente
            $index = array_rand($models);   // retourne un indice numérique aléatoire du tableau $models
            $model = $models[$index];       // retourne aléatoirement l'une des valeurs du tableau $models
            return [$brand, $model];
        }

        [$brand, $model] = getConstructorFromArray($brands);

        // création de 10 véhicules
        for ($i=0; $i <= 10; $i++) {
            $voiture = new Voiture();
            $voiture->setModel($model);
            $voiture->setConstructor($brand);
            $voiture->setEnergy($fuels[array_rand($fuels)]);
            $voiture->setSeat(mt_rand(1, 5));
            $voiture->setLength(mt_rand(3500, 4800) / 100);
            $voiture->setWidth(mt_rand(1600, 1900) / 100);
            $voiture->setWeight(mt_rand(1250, 1500));
        }

        // Ces méthodes et propriétés ont beau être signalées comme introuvables, elles fonctionnent
//        echo $faker->vehicle();
//        echo $faker->vehicleModel;
//        echo $faker->vehicleBrand;
//        echo $faker->vehicleFuelType;
//        echo $faker->vehicleSeatCount;

        echo $faker->name();
    }
}
