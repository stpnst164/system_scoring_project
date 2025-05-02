<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');

        $educations = [
            'Среднее',
            'Среднее специальное',
            'Высшее'
        ];

        $agreements = [
            'true',
            'false'
        ];


        for ($i = 0; $i < 20; $i++) {
            $client = new Client();
            $client -> setFirstName($faker -> firstName);
            $client -> setLastName($faker -> lastName);
            $client -> setPhoneNumber($faker -> unique() -> numerify('+7##########'));
            $client -> setEmail($faker -> email);
            $client -> setEducation($faker -> randomElement($educations));
            $client -> setGiveAgreement($faker -> randomElement($agreements));

            $manager->persist($client);
        }

        $manager->flush();
    }
}
