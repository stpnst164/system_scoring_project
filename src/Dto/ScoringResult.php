<?php

namespace App\Dto;

class ScoringResult
{
    public function __construct(
        readonly int $total,
        readonly array $breakdown
    )
    {}

    public function getTotal() :int
    {
        return $this -> total;
    }

    public function getBreakdown(): array
    {
        return $this->breakdown;
    }
}