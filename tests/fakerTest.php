<?php

namespace App\Tests;

use Faker\Factory;
use PHPUnit\Framework\TestCase;

class fakerTest extends TestCase
{
    private $faker;

    protected function setUp(): void
    {
        // The Faker\Factory will create a ready to use Faker Generator
        $this->faker = Factory::create();
        $this->faker->addProvider(new \Faker\Provider\Fakecar($this->faker));
    }

    public function testSomething(): void
    {
        $title = $this->faker->title();
        $this->assertNotEmpty($title, "pas vide");
    }
}