<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = (new \Faker\Factory())::create();
        $faker->addProvider(new \Faker\Provider\Fakecar($faker));

        echo $faker->vehicle();
        echo $faker->vehicleModel;
        echo $faker->vehicleBrand;
        echo $faker->vehicleFuelType;
        echo $faker->vehicleSeatCount;

        echo $faker->name();
    }
}
