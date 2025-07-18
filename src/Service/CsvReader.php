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
     * @return Event[]
     */
    public function readEvents(): array
    {
        $events = [];

        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("CSV not found: {$this->filePath}");
        }

        $file = fopen($this->filePath, 'r');
        if (false === $file) {
            throw new \RuntimeException("Failed to open CSV: {$this->filePath}");
        }

        while (($line = fgetcsv($file)) !== false) {
            /** @var list<string|null> $line */
            $line = array_map(
                fn ($value): ?string => is_string($value) ? trim($value) : null,
                $line
            );

            if (count($line) < 4) {
                continue; // Skip rows with missing fields
            }

            [$name, $location, $lat, $lng] = $line;

            if (!is_string($name) || !is_string($location)) {
                continue;
            }

            $address = new Address(
                latitude: $this->toFloat($lat),
                longitude: $this->toFloat($lng)
            );

            $name = $this->sanitizeForCsvInjection($name);
            $location = $this->sanitizeForCsvInjection($location);

            $events[] = new Event(
                name: $name,
                location: $location,
                address: $address
            );
        }

        fclose($file);

        return $events;
    }

    private function toFloat(?string $value): float
    {
        return is_numeric($value) ? (float) $value : 0.0;
    }

    public function getCacheKey(): string
    {
        $lastModified = file_exists($this->filePath) ? filemtime($this->filePath) : 0;

        return 'csv_events_'.$lastModified;
    }

    private function sanitizeForCsvInjection(string $value): string
    {
        $dangerous = ['=', '+', '-'];
        if (in_array(substr($value, 0, 1), $dangerous, true)) {
            return "'".$value; // Escape formula injection
        }

        return $value;
    }
}
