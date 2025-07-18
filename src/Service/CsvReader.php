<?php

namespace App\Service;

use App\Dto\Address;
use App\Dto\Event;

class CsvReader
{
    public function __construct(
        private string $filePath,
    ) {
    }

    /**
     * @param string|null $value
     */
    private function toFloat(?string $value): float
    {
        return is_numeric($value) ? (float) $value : 0.0;
    }

    /**
     * @return Event[]
     */
    public function readEvents(): array
    {
        $events = [];

        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("CSV not found: {$this->filePath}");
        }

        $file = fopen($this->filePath, 'r');
        if ($file === false) {
            throw new \RuntimeException("Failed to open CSV: {$this->filePath}");
        }

        while (($line = fgetcsv($file)) !== false) {
            /** @var list<string|null> $line */
            $line = array_map(
                fn ($value): string|null => is_string($value) ? trim($value) : null,
                $line
            );

            if (count($line) < 4 || $line[0] === null || $line[1] === null) {
                continue; // Skip rows with missing fields
            }

            $address = new Address(
                latitude: $this->toFloat($line[2]),
                longitude: $this->toFloat($line[3])
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
