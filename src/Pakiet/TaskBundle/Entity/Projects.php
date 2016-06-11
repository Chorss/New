<?php

namespace Pakiet\TaskBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="projects")
 */
class Projects
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(max=50)
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @Assert\Length(max=250)
     * @Assert\IsNull()
     *
     * @ORM\Column(type="string", nullable=true, length=250)
     */
    private $description;

    /**
     * @Assert\NotNull()
     * @Assert\DateTime()
     *
     * @ORM\Column(type="datetime", length=50)
     */
    private $dateCreated;

    /**
     * @Assert\DateTime()
     * @Assert\IsNull()
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\OneToMany(targetEntity="Tasks", mappedBy="projects")
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
     * @return Projects
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
     * Set description
     *
     * @param string $description
     *
     * @return Projects
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Projects
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return Projects
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Add task
     *
     * @param \Pakiet\TaskBundle\Entity\Tasks $task
     *
     * @return Projects
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