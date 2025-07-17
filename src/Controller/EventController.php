<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Service\CsvReader;
use Symfony\Component\Routing\Attribute\Route;

class EventController
{
    private CsvReader $reader;

    public function __construct(
        CsvReader $reader
    ) {
        $this->reader = $reader;
    }

    #[Route('/events')]
    public function events(): Response
    {
        $events = $this->reader->readEvents();
        $id = 1;

        return new Response(
            json_encode($events[$id] ?? null),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}