<?php

namespace app\validator\Event\rules;

use app\dto\Event\SafeEventDTO;
use Doctrine\ORM\EntityManager;

interface EventRuleInterface
{
    public static function validateRule(EntityManager $entityManager, SafeEventDTO $dto): string|bool;
}