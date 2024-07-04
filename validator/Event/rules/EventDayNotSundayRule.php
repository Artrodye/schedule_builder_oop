<?php

namespace app\validator\Event\rules;

use app\dto\Event\SafeEventDTO;
use Doctrine\ORM\EntityManager;

class EventDayNotSundayRule implements EventRuleInterface
{

    public static function validateRule(EntityManager $entityManager, SafeEventDTO $dto): string|bool
    {
        if (!isset($dto->beginTime)) {
            return 'Не передано время начала пары';
        }
        $beginTime = $dto->beginTime;

        if ((bool)date('w', strtotime($beginTime))) {
            return true;
        }
        return 'Попытка назначить пару в воскресенье';
    }
}