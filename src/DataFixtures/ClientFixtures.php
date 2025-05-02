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

        $educations = ['Среднее', 'Специальное', 'Высшее'];
        $phonePrefixes = [
            'МегаФон' => ['+7923', '+7929', '+7933', '+7999'],
            'Билайн'  => ['+7903'],
            'МТС'     => ['+7913', '+7983'],
            'Иной'    => ['+7950']
        ];
        $emailDomains = ['gmail.com', 'yandex.ru', 'mail.ru', 'example.com'];

        for ($i = 0; $i < 20; $i++) {
            $client = new Client();

            $client->setFirstName($faker->firstName);
            $client->setLastName($faker->lastName);

            $prefix = $faker->randomElement($phonePrefixes[$faker->randomElement(array_keys($phonePrefixes))]);
            $phone = $prefix . $faker->numerify('#######');
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