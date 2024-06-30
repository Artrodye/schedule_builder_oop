<?php

namespace app\Repository;

use app\entity\EventEntity;
use Doctrine\ORM\EntityRepository;

/**
 * @method EventEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventEntity[] findAll()
 * @method EventEntity[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventEntityRepository extends EntityRepository
{
}
