<?php

namespace App\Dto;

class Event
{
    public function __construct(
        public readonly string $name,
        public readonly string $location,
        public readonly Address $address,
    ) {
    }
}
