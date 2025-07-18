<?php

namespace App\Dto;

readonly class Event
{
    public function __construct(
        public string $name,
        public string $location,
        public Address $address,
    ) {
    }
}
