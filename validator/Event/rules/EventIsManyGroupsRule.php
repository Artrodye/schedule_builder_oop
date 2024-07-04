<?php

namespace app\validator\Event\rules;

use app\dto\Event\SafeEventDTO;
use Doctrine\ORM\EntityManager;

class EventIsManyGroupsRule implements EventRuleInterface
{

    public static function validateRule(EntityManager $entityManager, SafeEventDTO $dto): string|bool
    {
        if (!isset($dto->isManyGroups, $dto->groupsIds)) {
            return 'Не переданы параметры события';
        }
        if (!$dto->isManyGroups && count($dto->groupsIds) > 1) {
            return 'Превышено допустимое количество групп';
        }
        return true;
    }
}