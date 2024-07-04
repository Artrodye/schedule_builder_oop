<?php

namespace app\validator\Event\rules;

use app\dto\Event\SafeEventDTO;
use app\entity\EventEntity;
use Doctrine\ORM\EntityManager;

class EventTimeTeacherRule implements EventRuleInterface
{

    public static function validateRule(EntityManager $entityManager, SafeEventDTO $dto): string|bool
    {
        $id = 0;
        if (isset($dto->id)) {
            $id = $dto->id;
        }
        if (!isset($dto->beginTime, $dto->teacher)) {
            return 'Не переданы время и преподаватель события';
        }
        $beginTime = $dto->beginTime;
        $teacher = $dto->teacher;

        $events = $entityManager->getRepository(EventEntity::class)->findAll();
        foreach ($events as $event) {
            if (($event->getBeginTime() === $beginTime) && ($event->getTeacher() === $teacher) && ($event->getId() !== $id)) {
                return "Преподаватель в это время занят";
            }
        }
        return true;
    }
}