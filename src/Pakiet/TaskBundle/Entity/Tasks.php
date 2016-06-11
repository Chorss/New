<?php

namespace Pakiet\TaskBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Tasks
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
     * @Assert\Length(max=250)
     *
     * @ORM\Column(type="string", length=250)
     *
     */
    private $name;

    /**
     * @Assert\NotNull()
     * @Assert\DateTime()
     *
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Priorities", inversedBy="tasks")
     * @ORM\JoinColumn(name="priorities_id", referencedColumnName="id")
     */
    private $priorities;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="tasks")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     *
     *
     * @ORM\ManyToOne(targetEntity="Projects", inversedBy="tasks")
     * @ORM\JoinColumn(name="projects_id", referencedColumnName="id")
     */
    private $projects;

    /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="Labels", inversedBy="tasks")
     * @ORM\JoinColumn(name="labels_id", referencedColumnName="id")
     */
    private $labels;
    

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
     * @return Tasks
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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Tasks
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
     * Set priorities
     *
     * @param \Pakiet\TaskBundle\Entity\Priorities $priorities
     *
     * @return Tasks
     */
    public function setPriorities(\Pakiet\TaskBundle\Entity\Priorities $priorities = null)
    {
        $this->priorities = $priorities;

        return $this;
    }

    /**
     * Get priorities
     *
     * @return \Pakiet\TaskBundle\Entity\Priorities
     */
    public function getPriorities()
    {
        return $this->priorities;
    }

    /**
     * Set status
     *
     * @param \Pakiet\TaskBundle\Entity\Status $status
     *
     * @return Tasks
     */
    public function setStatus(\Pakiet\TaskBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Pakiet\TaskBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set projects
     *
     * @param \Pakiet\TaskBundle\Entity\Projects $projects
     *
     * @return Tasks
     */
    public function setProjects(\Pakiet\TaskBundle\Entity\Projects $projects = null)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * Get projects
     *
     * @return \Pakiet\TaskBundle\Entity\Projects
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set labels
     *
     * @param \Pakiet\TaskBundle\Entity\Labels $labels
     *
     * @return Tasks
     */
    public function setLabels(\Pakiet\TaskBundle\Entity\Labels $labels = null)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Get labels
     *
     * @return \Pakiet\TaskBundle\Entity\Labels
     */
    public function getLabels()
    {
        return $this->labels;
    }
}
