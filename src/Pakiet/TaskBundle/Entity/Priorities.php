<?php

namespace Pakiet\TaskBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="priorities")
 */
class Priorities
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Tasks", mappedBy="priorities", cascade={"remove"} )
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Priorities
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add task
     *
     * @param \Pakiet\TaskBundle\Entity\Tasks $task
     *
     * @return Priorities
     */
    public function addTask(\Pakiet\TaskBundle\Entity\Tasks $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task
     *
     * @param \Pakiet\TaskBundle\Entity\Tasks $task
     */
    public function removeTask(\Pakiet\TaskBundle\Entity\Tasks $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}