<?php

namespace App\Service;

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
            $events[] = [
                'event_name'=> $line[0],
                'location' => $line[1],
                'address' => [
                    'latitude' => (float) $line[2],
                    'longitude' => (float) $line[3],
                ]
            ];
        }

        fclose($file);

        return $events;
    }

}
