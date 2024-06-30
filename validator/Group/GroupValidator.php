<?php

namespace app\validator\Group;
use app\ApplicationException\ApplicationException;
use app\dto\Group\SafeGroupDTO;
use app\Service\GroupService;
use Doctrine\ORM\EntityManager;
use app\validator\Group\rules\GroupNameRule;

class GroupValidator extends GroupService
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function validateGroup(SafeGroupDTO $dto): true
    {
        $collection = [];
        $rules = [GroupNameRule::class];
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