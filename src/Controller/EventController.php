<?php

namespace App\Controller;

use App\Dto\Event;
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
        $response = array_map(fn(Event $e) => $e->toArray(), $events);

        return new JsonResponse($response);
    }

    #[Route('/events/{id}', name: 'event_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $events = $this->reader->readEvents();
        $index = $id - 1;

        if (!isset($events[$index])) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }

        return new JsonResponse($events[$index]->toArray());
    }
}