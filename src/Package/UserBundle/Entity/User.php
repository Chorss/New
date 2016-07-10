<?php

namespace Package\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Package\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements AdvancedUserInterface, \Serializable
{
    //@todo Dodać Assert dla pól
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @ORM\Column(type="string", length=150, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="5", max="120")
     * @Assert\Type("string")
     */
    private $plainPassword;

    /**
     * @Assert\Type("array")
     *
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="string", name="action_token", length = 30, nullable = true)
     */
    private $actionToken;

    /**
     * @ORM\Column(type="boolean", name="account_non_expired")
     */
    private $accountNonExpired = true;

    /**
     * @ORM\Column(type="boolean", name="account_non_locked")
     */
    private $accountNonLocked = true;

    /**
     * @ORM\Column(type="boolean", name="credentials_non_expired")
     */
    private $credentialsNonExpired = true;

    /**
     * @ORM\Column(type="boolean", name="enabled")
     */
    private $isEnabled = false;

    /**
     * @ORM\Column(type="datetime", name="date_register")
     */
    private $dateRegister;

    /**
     * @ORM\Column(type="datetime", name="last_activity", nullable=true)
     */
    private $lastActivity = null;

    /**
     * @ORM\OneToMany(targetEntity="Package\TaskBundle\Entity\Tasks", mappedBy="author")
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->dateRegister = new \DateTime();
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }

    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }
    
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isEnabled,
            $this->lastActivity,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isEnabled,
            $this->lastActivity,
            ) = unserialize($serialized);
    }

}