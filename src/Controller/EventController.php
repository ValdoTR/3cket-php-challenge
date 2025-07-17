<?php

namespace App\Controller;

use App\Dto\Event;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CsvReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    public function __construct(
        private CsvReader $reader
    ) {}

    #[Route('/events', name: 'event_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $events = $this->reader->readEvents();
        return $this->json($events);
    }

    #[Route('/events/{id}', name: 'event_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $events = $this->reader->readEvents();
        $index = $id - 1;

        if (!isset($events[$index])) {
            return $this->json(['error' => 'Not found'], 404);
        }

        return $this->json($events[$index]);
    }
}