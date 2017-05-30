<?php

namespace Package\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Package\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UsersFixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail("wa@wp.pl");
        $admin->setPassword(
            $encoder->encodePassword($admin, 'admin')
        );
        $admin->setRoles(array('ROLE_ADMIN'));
        $admin->setIsEnabled(true);
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('user');
        $user->setEmail("wu@wp.pl");
        $user->setPassword(
            $encoder->encodePassword($user, 'user')
        );
        $user->setRoles(array('ROLE_USER'));
        $user->setIsEnabled(true);
        $manager->persist($user);

        $nikk = new User();
        $nikk->setUsername('nikk');
        $nikk->setEmail("wn@wp.pl");
        $nikk->setPassword(
            $encoder->encodePassword($nikk, 'nikk')
        );
        $nikk->setRoles(array('ROLE_USER'));
        $nikk->setIsEnabled(true);
        $manager->persist($nikk);

        $manager->flush();
    }
}