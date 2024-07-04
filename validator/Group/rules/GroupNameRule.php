<?php

namespace app\validator\Group\rules;

use app\dto\Group\SafeGroupDTO;
use app\entity\GroupEntity;
use Doctrine\ORM\EntityManager;

class GroupNameRule implements GroupRuleInterface
{

    static function validateRule(EntityManager $entityManager, SafeGroupDTO $dto): string|bool
    {
        if (is_null($dto->name)) {
            return 'Не передано имя группы';
        }
        $groups = $entityManager->getRepository(GroupEntity::class)->findAll();
        foreach ($groups as $group) {
            if (($group->getName() === $dto->name) && ($dto->id !== $group->getId())) {
                return 'Группа с таким именем уже существует';
            }
        }
        return true;
    }


}