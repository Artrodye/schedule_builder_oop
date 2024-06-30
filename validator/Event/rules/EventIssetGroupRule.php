<?php

namespace app\validator\Event\rules;

use app\dto\Event\SafeEventDTO;
use app\entity\EventEntity;
use app\entity\GroupEntity;
use Doctrine\ORM\EntityManager;

class EventIssetGroupRule implements EventRuleInterface
{

    public static function validateRule(EntityManager $entityManager, SafeEventDTO $dto): string|bool
    {
        if (!isset($dto->groupsIds)) {
            return "Не переданы группы события";
        }
        $groupsIds = $dto->groupsIds;

        $listOfGroups = [];
        foreach ($entityManager->getRepository(GroupEntity::class)->findAll() as $group) {
            $listOfGroups[] = $group->getId();
        }
        foreach ($groupsIds as $groupId) {
            if (!in_array($groupId, $listOfGroups)) {
                return "Указанной группы не существует";
            }
        }
        return true;
    }
}