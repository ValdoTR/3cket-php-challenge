<?php

namespace App\Service;

use App\Dto\Address;
use App\Dto\Event;

class CsvReader
{
    public function __construct(
        private string $filePath
    ) {}

    public function readEvents(): array
    {
        $events = [];

        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("CSV not found: {$this->filePath}");
        }

        $file = fopen($this->filePath, 'r');

        while (($line = fgetcsv($file)) !== false) {
            $line = array_map('trim', $line); // remove extra spaces

            if (count($line) < 4) {
                continue; // Skip malformed rows
            }

            $address = new Address(
                latitude: (float) $line[2],
                longitude: (float) $line[3]
            );

            $events[] = new Event(
                name: $line[0],
                location: $line[1],
                address: $address
            );
        }

        fclose($file);

        return $events;
    }
}
