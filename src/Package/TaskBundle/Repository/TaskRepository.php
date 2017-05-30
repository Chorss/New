<?php

namespace Package\TaskBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    public function getQueryPagination()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('t', 's', 'p')
            ->from('PackageTaskBundle:Task', 't')
            ->innerJoin('t.status', 's')
            ->innerJoin('t.priority', 'p');

        return $qb->getQuery();
    }
}