<?php

namespace App\Service;

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
            if (count($line) < 4) {
                continue; // Skip malformed rows
            }

             $events[] = new Event(
                name: $line[0],
                location: $line[1],
                address_latitude: (float) $line[2],
                address_longitude: (float) $line[3]
            );
        }

        fclose($file);

        return $events;
    }
}
