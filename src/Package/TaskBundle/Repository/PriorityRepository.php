<?php

namespace Package\TaskBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PriorityRepository extends EntityRepository
{
    public function getQueryPagination()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('p')
            ->from('PackageTaskBundle:Priority', 'p');

        return $qb->getQuery();
    }
}