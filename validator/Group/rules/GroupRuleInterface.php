<?php

namespace app\validator\Group\rules;

use app\dto\Group\SafeGroupDTO;
use Doctrine\ORM\EntityManager;

interface GroupRuleInterface
{
    public static function validateRule(EntityManager $entityManager, SafeGroupDTO $dto): string|bool;
}