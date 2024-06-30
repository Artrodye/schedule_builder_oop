<?php

namespace app\dto\Event;

class SafeEventDTO
{
    public ?int $id;
    public string $name;
    public string $beginTime;
    public string $room;
    public string $teacher;
    public int $isManyGroups;
    public ?string $comment;
    public array $groupsIds;
    public ?int $times;
    public ?string $period;
}