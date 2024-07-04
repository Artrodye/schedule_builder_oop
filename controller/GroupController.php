<?php

namespace app\controller;

use app\Container\Container;
use app\dto\Group\SafeGroupDTO;
use app\http\Request;
use app\Service\GroupService;

class GroupController
{
    public function __construct(
        private readonly GroupService $groupService,
    )
    {
    }

    public function getEventsForGroup(Request $request): array
    {
        $dto = (new Container())->get(SafeGroupDTO::class);
        $dto->id = $request->getBodyValue('id');
        return $this->groupService->getEventsForGroup($dto);
    }
    public function getAll(Request $request): array
    {
        return $groups = $this->groupService->getAll();
    }

    public function getGroup(Request $request): array
    {
        return $this->groupService->getGroup($request->getBodyValue('id'));
    }

    public function create(Request $request): void
    {
        $dto = (new Container())->get(SafeGroupDTO::class);
        $dto->id = $request->getBodyValue('id');
        $dto->name = $request->getBodyValue('name');
        $this->groupService->create($dto);
    }

    public function delete(Request $request): void
    {
        $this->groupService->delete($request->getBodyValue("id"));
    }

    public function update(Request $request): void
    {
        $dto = (new Container())->get(SafeGroupDTO::class);
        $dto->id = $request->getBodyValue('id');
        $dto->name = $request->getBodyValue('name');
        $this->groupService->update($dto);
    }
}