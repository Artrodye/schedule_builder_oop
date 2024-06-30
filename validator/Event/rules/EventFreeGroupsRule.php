<?php

namespace app\validator\Event\rules;

use app\dto\Event\SafeEventDTO;
use app\entity\EventEntity;
use Doctrine\ORM\EntityManager;

class EventFreeGroupsRule implements EventRuleInterface
{

    public static function validateRule(EntityManager $entityManager, SafeEventDTO $dto): string|bool
    {
        $id = 0;
        if (isset($dto->id)) {
            $id = $dto->id;
        }
        if (!isset($dto->beginTime, $dto->groupsIds)) {
            return 'Не переданы время и группы события';
        }
        $beginTime = $dto->beginTime;
        $groupsIds = $dto->groupsIds;

        $events = $entityManager->getRepository(EventEntity::class)->findAll();
        $failureGroups = [];
        foreach ($events as $event) {
            if (($beginTime === $event->getBeginTime()) && ($id !== $event->getId())) {
                $failureGroups = array_merge($failureGroups, array_intersect($groupsIds, $event->getGroups()));
            }
        }
        $failureGroups = array_unique($failureGroups);
        if (!count($failureGroups)) {
            return true;
        }
        return 'У групп ' . implode(', ', $failureGroups) . ' пары в это время';
    }
}