<?php

namespace App\Service;

use App\Dto\ScoringResult;
use App\Entity\Client;

class ScoringService
{
    //Функция суммирования баллов
    public function calculate(Client $client) :ScoringResult {
        $total = 0;
        //Список правил с баллами
        //* Сотовый оператор.
        // МегаФон - 10 баллов, Билайн - 5, МТС - 3, Иной - 1.
        $operatorScores =
            [
                'МегаФон' => 10,
                'Билайн' => 5,
                'МТС' => 3,
                'Иной' => 1
            ];

        //Вычисление оператора по коду номера телефона
        $operator = $this -> detectOperator($client -> getPhoneNumber());

        //Подсчет вычисляется по оператору. Если используется другой оператор - присвоится 1 балл
        $score = $operatorScores[$operator] ?? 1;

        $total += $score;


        //* Домен Э-почты. gmail - 10, yandex - 8, mail - 6, Иной - 3.
        $emailDomainScores = [
            'gmail.com' => 10,
            'yandex.ru' => 8,
            'mail.ru' => 6,
            'Иной' => 3
        ];

        //Вычисление домена э-почты
        $emailDomain = $this -> extractEmailDomain($client -> getEmail());

        $score = $emailDomainScores[$emailDomain] ?? 3;

        $total += $score;


        //* Образование. Высшее образование - 15, Специальное образование - 10, Среднее образование - 5.
        $educationScores = [
            'Высшее' => 15,
            'Специальное' => 10,
            'Среднее' => 5
        ];

        $education = $client -> getEducation();

        $score = $educationScores[$education];

        $total += $score;


        //* Галочка "Я даю согласие на обработку моих личных данных". Выбрана - 4, Не выбрана - 0
        $agreement = $client -> isGiveAgreement() ? 4 : 0;

        $score = $agreement;

        $total += $score;


        return new ScoringResult($total);
    }

    //Определение оператора
    private function detectOperator(string $phoneNumber): string
    {
        //Удаление всех нецифровых символов
        $clean = preg_replace('/[^0-9]/', '', $phoneNumber);

        //Удаление +7 или 8 для оставления кода оператора
        if (str_starts_with($clean, '7') && strlen($clean) === 11) {
            $code = substr($clean, 1, 3);
        } elseif (str_starts_with($clean, '8') && strlen($clean) === 11) {
            $code = substr($clean, 1, 3);
        } elseif (strlen($clean) === 10) {
            $code = substr($clean, 0, 3);
        } else {
            return 'Иной';
        }

        $operatorCodes = [
            'МегаФон' => ['923', '929', '933', '999'],
            'Билайн' => ['903'],
            'МТС' => ['913', '983']
        ];

        //Цикл проходится по операторам для дальнейшего присваивания очков за оператора
        foreach ($operatorCodes as $name => $prefixes) {
            if (in_array($code, $prefixes, true)) {
                return $name;
            }
        }

        return 'Иной';
    }

    //Функция извлечения домена э-почты
    private function extractEmailDomain(string $email) :string {
        $parts = explode('@', $email);
        return $parts[1];


        return 'Иной';
    }
}