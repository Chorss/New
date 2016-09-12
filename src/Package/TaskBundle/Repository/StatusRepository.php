<?php

namespace Package\TaskBundle\Repository;

use Doctrine\ORM\EntityRepository;

class StatusRepository extends EntityRepository
{
    public function getQueryPagination()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('s')
            ->from('PackageTaskBundle:Status', 's');

        return $qb->getQuery();
    }
}