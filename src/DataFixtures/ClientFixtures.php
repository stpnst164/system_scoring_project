<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Service\ScoringService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    private ScoringService $scoringService;

    public function __construct(ScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');

        $operators = [
            'МегаФон' => ['923', '929', '933', '999'],
            'Билайн' => ['903'],
            'МТС' => ['913', '983'],
            'Иной' => []
        ];
        $emailDomains = [
            'gmail.com',
            'yandex.ru',
            'mail.ru'
        ];
        $educations = ['Высшее', 'Специальное', 'Среднее'];


        for ($i = 0; $i < 20; $i++) {
            $client = new Client();

            $client->setFirstName($faker->firstName);
            $client->setLastName($faker->lastName);

            $prefix = $faker->randomElement($operators[$faker->randomElement(array_keys($operators))]);
            $phone = '+7' . $prefix . $faker->numerify('#######');
            $client->setPhoneNumber($phone);

            $domain = $faker->randomElement($emailDomains);
            $email = $faker->userName . '@' . $domain;
            $client->setEmail($email);

            $client->setEducation($faker->randomElement($educations));
            $client->setGiveAgreement($faker->boolean());

            // Подсчёт скоринга
            $scoring = $this->scoringService->calculate($client)->getTotal();
            $client->setScoring($scoring);

            $manager->persist($client);
        }

        $manager->flush();
    }
}