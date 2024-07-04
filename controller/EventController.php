<?php

namespace app\Controller;

use app\Container\Container;
use app\dto\Event\SafeEventDTO;
use app\http\Request;
use app\Service\EventService;

readonly class EventController
{
    public function __construct(
        private EventService $eventService,
    )
    {
    }

    public function getAll(): array // Request
    {
        $groups = $this->eventService->getAll();

        return $groups;
    }

    public function create(Request $request): void
    {
        $dto = (new Container())->get(SafeEventDTO::class);
        $dto->name = $request->getBodyValue('name');
        $dto->beginTime = \DateTime::createFromFormat('Y-m-d H:i:s', $request->getBodyValue('beginTime'))->format('Y-m-d H:i:s');
        $dto->room = $request->getBodyValue('room');
        $dto->teacher = $request->getBodyValue('teacher');
        $dto->isManyGroups = $request->getBodyValue('isManyGroups');
        $dto->comment = $request->getBodyValue('comment');
        $dto->groupsIds = $request->getBodyValue('groupsIds');
        $dto->times = $request->getBodyValue('times');
        $dto->period = $request->getBodyValue('period');
        $this->eventService->create($dto);
    }

    public function delete(Request $request): void
    {
        $this->eventService->delete($request->getBodyValue("id"));
    }

    public function update(Request $request): void
    {
        $dto = (new Container())->get(SafeEventDTO::class);
        $dto->id = $request->getBodyValue('id');
        $dto->name = $request->getBodyValue('name');
        $dto->beginTime = \DateTime::createFromFormat('Y-m-d H:i:s', $request->getBodyValue('beginTime'))->format('Y-m-d H:i:s');
        $dto->room = $request->getBodyValue('room');
        $dto->teacher = $request->getBodyValue('teacher');
        $dto->isManyGroups = $request->getBodyValue('isManyGroups');
        $dto->comment = $request->getBodyValue('comment');
        $dto->groupsIds = $request->getBodyValue('groupsIds');
        $this->eventService->update($dto);
    }
}