<?php

namespace app\validator\Event\rules;

use app\dto\Event\SafeEventDTO;
use app\entity\EventEntity;
use Doctrine\ORM\EntityManager;

class EventTimeRoomRule implements EventRuleInterface
{

    public static function validateRule(EntityManager $entityManager, SafeEventDTO $dto): string|bool
    {
        $id = 0;
        if (isset($dto->id)) {
            $id = $dto->id;
        }
        if (!isset($dto->beginTime, $dto->room)) {
            return 'Не переданы время и аудитория события';
        }
        $beginTime = $dto->beginTime;
        $room = $dto->room;

        $events = $entityManager->getRepository(EventEntity::class)->findAll();
        foreach ($events as $event) {
            if (($event->getBeginTime() === $beginTime) && ($event->getRoom() === $room) && ($event->getId() !== $id)) {
                return "Аудитория в это время занята";
            }
        }
        return true;
    }
}