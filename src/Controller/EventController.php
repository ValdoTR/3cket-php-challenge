<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CsvReader;

class EventController
{
    private CsvReader $reader;

    public function __construct(
        CsvReader $reader
    ) {
        $this->reader = $reader;
    }

    #[Route('/events', name: 'event_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $events = $this->reader->readEvents();

        return new JsonResponse($events);
    }

    #[Route('/events/{id}', name: 'event_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $events = $this->reader->readEvents();

        return new JsonResponse($events[$id - 1]);
    }
}