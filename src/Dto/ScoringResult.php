<?php

namespace App\Dto;

class ScoringResult
{

    public function __construct(
        public int $total
    )
    {}

    public function getTotal() :int
    {
        return $this -> total;
    }
}