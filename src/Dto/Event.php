<?php

namespace App\Dto;

class Event
{
    public function __construct(
        public readonly string $name,
        public readonly string $location,
        public readonly float $address_latitude,
        public readonly float $address_longitude,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'location' => $this->location,
            'address' => [
                'latitude' => $this->address_latitude,
                'longitude' => $this->address_longitude,
            ]
        ];
    }
}
