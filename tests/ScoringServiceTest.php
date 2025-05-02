<?php

namespace App\Tests\Service;

use App\Entity\Client;
use App\Service\ScoringService;
use PHPUnit\Framework\TestCase;

class ScoringServiceTest extends TestCase
{
    private ScoringService $service;

    // Вызывается перед каждым тестом
    protected function setUp(): void
    {
        $this->service = new class extends ScoringService {
            public function testDetectOperator(string $phoneNumber): string
            {
                return $this->detectOperator($phoneNumber);
            }

            public function testExtractEmailDomain(string $email): string
            {
                return $this->extractEmailDomain($email);
            }
        };
    }

    public function testCalculateMaxScore(): void
    {
        $client = (new Client())
            ->setPhoneNumber('+79231234567') // МегаФон
            ->setEmail('test@gmail.com')     // gmail
            ->setEducation('Высшее')
            ->setGiveAgreement(true);

        $result = $this->service->calculate($client);

        $this->assertEquals(39, $result->getTotal());
        $this->assertCount(4, $result->getBreakdown());
    }

    public function testCalculateMinScore(): void
    {
        $client = (new Client())
            ->setPhoneNumber('81234567890') // Иной
            ->setEmail('user@custom.org')   // Иной
            ->setEducation('Среднее')
            ->setGiveAgreement(false);

        $result = $this->service->calculate($client);

        // Иной оператор = 1, Иной домен = 3, Среднее = 5, Нет согласия = 0
        $this->assertEquals(9, $result->getTotal());
    }

    public function testDetectOperator(): void
    {
        $operator = $this->service->testDetectOperator('11111111111');
        $this->assertEquals('Иной', $operator);
    }

    public function testExtractEmailDomain(): void
    {
        $domain = $this->service->testExtractEmailDomain('john@domain.ru');
        $this->assertEquals('domain.ru', $domain);
    }
}