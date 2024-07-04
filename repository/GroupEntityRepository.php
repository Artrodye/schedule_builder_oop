<?php

namespace app\repository;

use app\dto\Group\SafeGroupDTO;
use app\Entity\EventEntity;
use app\Entity\GroupEntity;
use Doctrine\ORM\EntityRepository;

/**
 * @method GroupEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupEntity[] findAll()
 * @method GroupEntity[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupEntityRepository extends EntityRepository
{

    public function getEventsForGroup(SafeGroupDTO $dto): array
    {
        $queryBuilder = getEntityManager()->createQueryBuilder();
        $query = $queryBuilder
            ->select('events.name', 'events.beginTime', 'events.teacher', 'events.room', 'events.comment')
            ->from(EventEntity::class, 'events')
            ->join('events.groups', 'groups', 'events.id = groups.event_id')
            ->where("groups.id = :groupId")
            ->orderBy('events.beginTime', 'ASC')
            ->setParameter("groupId", $dto->id)
            ->getQuery();
        $result = $query->getResult();
        return $result;
    }
}