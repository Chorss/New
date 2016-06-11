<?php

namespace Pakiet\TaskBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="status")
 */
class Status
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     max=50,
     *     maxMessage = "Your titleee cannot be longer than {{ limit }} characters"
     * )
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Tasks", mappedBy="status", cascade={"remove"})
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
     * @return Status
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
     * @return Status
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