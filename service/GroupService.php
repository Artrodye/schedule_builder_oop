<?php

namespace app\Service;

use app\ApplicationException\ApplicationException;
use app\dto\Group\SafeGroupDTO;
use app\entity\EventEntity;
use app\entity\GroupEntity;
use app\validator\Group\GroupValidator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use mysql_xdevapi\Result;

class GroupService
{
    public function __construct(
        protected readonly EntityManager $entityManager,
    )
    {
    }

    public function getEventsForGroup(SafeGroupDTO $dto): array
    {
        if (is_null($dto->id)) {
            throw new ApplicationException("Не передан идентификатор группы", 400);
        }
        $queryBuilder = $this->entityManager->createQueryBuilder(); // Я бы скорее создавал билдер отдельно в конструкторе, если бы он использовался во всех методах, но здесь я не вижу в этом смысла
        $query = $queryBuilder
            ->select('events.name', 'events.beginTime', 'events.teacher', 'events.room', 'events.comment')
            ->from(EventEntity::class, 'events')
            ->join('events.groups', 'groups', 'events.id = groups.event_id')
            ->where("groups.id = :groupId")
            ->setParameter(key: "groupId", value: $dto->id)
            ->getQuery();
        $result = $query->getResult();
        usort($result, function ($first, $second) {return $first["beginTime"] <=> $second["beginTime"];});
        return $result;
    }
    public function getAll(): array
    {
        $groups = $this->entityManager->getRepository(GroupEntity::class)->findAll();
        $result = [];
        foreach ($groups as $group) {
            $result[] = [
                "id" => $group->getId(),
                "name" => $group->getName()
            ];
        }
        return $result;
    }

    public function getGroup(int $id): array
    {
        $group = $this->entityManager->getRepository(GroupEntity::class)->find($id);
        return ["id" => $group->getId(), "name" => $group->getName(), "events" => $group->getEvents()];
    }

    public function delete(int $id)
    {
        try {
            $group = $this->entityManager->getRepository(GroupEntity::class)->find($id);
            if (is_null($group)) {
                throw new ApplicationException("Данной группы не существует", 404);
            }
            $this->entityManager->remove($group);
            $this->entityManager->flush();
        } catch (ApplicationException $applicationException) {
            throw $applicationException;
        } catch (ORMException $ORMException) {
            throw new ApplicationException("Возникла ошибка при выполнении", 500);
        }
    }

    public function update(SafeGroupDTO $dto)
    {
        try {
            if (!isset($dto->id, $dto->name)) {
                throw new ApplicationException("Не переданы параметры группы", 400);
            }
            $id = $dto->id;
            $name = $dto->name;
            $group = $this->entityManager->getRepository(GroupEntity::class)->find($id);
            if (is_null($group)) {
                throw new ApplicationException("Данной группы не существует", 404);
            }
            if ((new GroupValidator($this->entityManager))->validateGroup($dto)) {
                $group->setName($name);
                $this->entityManager->flush();
            }
        } catch (ApplicationException $applicationException) {
            throw $applicationException;
        } catch (ORMException $ORMException) {
            throw new ApplicationException("Возникла ошибка при выполнении", 500);
        }
    }

    public function create(SafeGroupDTO $dto)
    {
        try {
            if ((new GroupValidator($this->entityManager))->validateGroup($dto)) {
                $group = new GroupEntity();
                $group->setName($dto->name);
                $this->entityManager->persist($group);
                $this->entityManager->flush();
            }
        } catch (ApplicationException $applicationException) {
            throw $applicationException;
        } catch (ORMException $ORMException) {
            throw new ApplicationException("Возникла ошибка при выполнении", 500);
        }
    }
}
