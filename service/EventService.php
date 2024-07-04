<?php

namespace app\service;

use app\ApplicationException\ApplicationException;
use app\dto\Event\SafeEventDTO;
use app\entity\EventEntity;
use app\entity\GroupEntity;
use app\validator\Event\EventValidator;
use DateInterval;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

class EventService
{
    public function __construct(
        protected readonly EntityManager $entityManager,
    )
    {
    }

    public function getAll(): array
    {
        $events = $this->entityManager->getRepository(EventEntity::class)->findAll();
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                "id" => $event->getId(),
                "name" => $event->getName(),
                "beginTime" => $event->getBeginTime(),
                "room" => $event->getRoom(),
                "teacher" => $event->getTeacher(),
                "isManyGroups" => $event->getIsManyGroups(),
                "comment" => $event->getComment(),
                "groupsIds" => $event->getGroups()
            ];
        }
        return $result;
    }

    public function create(SafeEventDTO $dto)
    {
        try {
            $this->entityManager->getConnection()->beginTransaction();
            if (!isset($dto->times, $dto->period)) {
                throw new ApplicationException("Не переданы параметры события для периодизации", 400);
            }
            $times = $dto->times;
            $period = $dto->period;
            foreach (range(1, $times) as $time) {
                if ((new EventValidator($this->entityManager))->validateEvent($dto)) {
                    $event = new EventEntity();
                    $event->setName($dto->name);
                    $event->setBeginTime($dto->beginTime);
                    $event->setRoom($dto->room);
                    $event->setTeacher($dto->teacher);
                    $event->setIsManyGroups($dto->isManyGroups);
                    $event->setComment($dto->comment);
                    foreach ($dto->groupsIds as $groupId) {
                        $event->addGroupsCollection($this->entityManager->getRepository(GroupEntity::class)->find($groupId));
                    }
                    $this->entityManager->persist($event);
                    $this->entityManager->flush();

                    $beginTime = \DateTime::createFromFormat("Y-m-d H:i:s", $dto->beginTime);
                    $beginTime->add(new DateInterval('P' . $period));
                    $dto->beginTime = $beginTime->format("Y-m-d H:i:s");
                }
            }
            $this->entityManager->getConnection()->commit();
        } catch (ApplicationException $applicationException) {
            $this->entityManager->getConnection()->rollBack();
            throw $applicationException;
        } catch (ORMException $ORMException) {
            $this->entityManager->getConnection()->rollBack();
            throw new ApplicationException("Возникла ошибка при выполнении", 500);
        }
    }

    public function delete(int $id)
    {
        $event = $this->entityManager->getRepository(EventEntity::class)->find($id);
        if (is_null($event)) {
            throw new ApplicationException("Данного события не существует", 400);
        }
        $this->entityManager->remove($event);
        $this->entityManager->flush();
    }

    public function update(SafeEventDTO $dto)
    {
        try {
            $event = $this->entityManager->getRepository(EventEntity::class)->find($dto->id);
            if (is_null($event)) {
                throw new ApplicationException("Данного события не существует", 400);
            }
            if ((new EventValidator($this->entityManager))->validateEvent($dto)) {
                $event->setName($dto->name);
                $event->setBeginTime($dto->beginTime);
                $event->setRoom($dto->room);
                $event->setTeacher($dto->teacher);
                $event->setIsManyGroups($dto->isManyGroups);
                $event->setComment($dto->comment);
                $event->clearGroupsCollection();
                foreach ($dto->groupsIds as $groupId) {
                    $event->addGroupsCollection($this->entityManager->getRepository(GroupEntity::class)->find($groupId));
                }
                $this->entityManager->persist($event);
                $this->entityManager->flush();
            }
        } catch (ApplicationException $applicationException) {
            throw $applicationException;
        } catch (ORMException $ORMException) {
            throw new ApplicationException("Возникла ошибка при выполнении", 500);
        }
    }
}