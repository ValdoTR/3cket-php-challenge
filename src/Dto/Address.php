<?php

namespace App\Dto;

class Address
{
    public function __construct(
        public float $latitude,
        public float $longitude,
    ) {}
}
