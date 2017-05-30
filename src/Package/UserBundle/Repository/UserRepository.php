<?php

namespace Package\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Package\UserBundle\Entity\User;

class UserRepository extends EntityRepository
{
    /**
     * findUserByUsername
     *
     * @param String $username
     * @return User/Null
     */
    public function findUserByUsername(String $username)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from('PackageUserBundle:User', 'u')
            ->where("u.username = :username")
            ->setParameter("username", $username);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * findUserByActionToken
     *
     * @param String $actionToken
     * @return User/Null
     */
    public function findUserByActionToken(String $actionToken)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from('PackageUserBundle:User', 'u')
            ->where("u.actionToken = :actionToken")
            ->setParameter("actionToken", $actionToken);

        return $qb->getQuery()->getOneOrNullResult();
    }


    /**
     * findUserByEmail
     *
     * @param String $email
     * @return User/Null
     */
    public function findUserByEmail(String $email)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from('PackageUserBundle:User', 'u')
            ->where("u.email = :email")
            ->setParameter("email", $email);

        return $qb->getQuery()->getOneOrNullResult();
    }
}