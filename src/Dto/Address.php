<?php

namespace App\Dto;

readonly class Address
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {
    }
}
