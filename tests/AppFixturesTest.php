<?php

namespace App\Tests;

use App\DataFixtures\AppFixtures;
use PHPUnit\Framework\TestCase;

class AppFixturesTest extends TestCase
{
    public function testFixture()
    {
        $fixture = new AppFixtures();

        // test de l'état de la propriété constructors
        $this->assertNotEmpty($fixture->constructors);
        $this->assertCount(6, $fixture->constructors);

        // test de la taille de la propriété fixtureBrands après exécution de la méthode getConstructors
        $this->assertEmpty($fixture->fixtureBrands);
        $fixture->getConstructors();
        $this->assertNotEmpty($fixture->fixtureBrands);

        // on teste l'état vide la propriété vehicles
        $this->assertCount(0, $fixture->vehicles);

        // test de la méthode getModelFromArray
        [$constructor] = $fixture->getModelFromArray($fixture->constructors);
        $this->assertIsString($constructor);

        // test de la taille de la propriété vehicles après exécution de la méthode getVehicles
        $fixture->getVehicles();
        $this->assertCount(10, $fixture->vehicles);

        // test de getConstructorObjectFromName
        $brand = $fixture->getConstructorObjectFromName($fixture->fixtureBrands, $constructor);
        $this->assertNotNull($brand);
        print_r($brand);
    }
}