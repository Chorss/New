<?php

namespace Package\TaskBundle\Repository;

use Doctrine\ORM\EntityRepository;

class LabelRepository extends EntityRepository
{
    public function getQueryPagination()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('l')
            ->from('PackageTaskBundle:Label', 'l');

        return $qb->getQuery();
    }
}