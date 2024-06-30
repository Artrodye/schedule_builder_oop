<?php

namespace app\validator\Event;
use app\ApplicationException\ApplicationException;
use app\dto\Event\SafeEventDTO;
use app\Service\EventService;
use app\validator\Event\rules\EventDayNotSundayRule;
use app\validator\Event\rules\EventFreeGroupsRule;
use app\validator\Event\rules\EventIsManyGroupsRule;
use app\validator\Event\rules\EventIssetGroupRule;
use app\validator\Event\rules\EventTimeRoomRule;
use app\validator\Event\rules\EventTimeTeacherRule;
use Doctrine\ORM\EntityManager;

class EventValidator extends EventService
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function validateEvent(SafeEventDTO $dto): true
    {
        $collection = [];
        $rules = [EventTimeRoomRule::class, EventTimeTeacherRule::class, EventIssetGroupRule::class, EventDayNotSundayRule::class, EventIsManyGroupsRule::class, EventFreeGroupsRule::class];
        foreach ($rules as $rule) {
            $result = $rule::validateRule($this->entityManager, $dto);
            if (is_string($result)) {
                $collection[] = $result;
            }
        }
        if (!empty($collection)) {
            throw new ApplicationException(implode("\n", $collection), 400);
        }
        return true;
    }
}