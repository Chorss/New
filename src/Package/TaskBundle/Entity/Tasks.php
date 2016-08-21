<?php

namespace Package\TaskBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
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
     */
    private $name;

    /**
     * @Assert\NotNull()
     *
     * @ORM\ManyToOne(targetEntity="Package\UserBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @Assert\DateTime()
     * @Assert\NotNull()
     *
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
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
     * @ORM\ManyToOne(targetEntity="Labels", inversedBy="tasks")
     * @ORM\JoinColumn(name="labels_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $labels;

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
     * Set author
     *
     * @param \Package\UserBundle\Entity\User $author
     *
     * @return Tasks
     */
    public function setAuthor(\Package\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Package\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set priorities
     *
     * @param \Package\TaskBundle\Entity\Priorities $priorities
     *
     * @return Tasks
     */
    public function setPriorities(\Package\TaskBundle\Entity\Priorities $priorities = null)
    {
        $this->priorities = $priorities;

        return $this;
    }

    /**
     * Get priorities
     *
     * @return \Package\TaskBundle\Entity\Priorities
     */
    public function getPriorities()
    {
        return $this->priorities;
    }

    /**
     * Set status
     *
     * @param \Package\TaskBundle\Entity\Status $status
     *
     * @return Tasks
     */
    public function setStatus(\Package\TaskBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Package\TaskBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set labels
     *
     * @param \Package\TaskBundle\Entity\Labels $labels
     *
     * @return Tasks
     */
    public function setLabels(\Package\TaskBundle\Entity\Labels $labels = null)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Get labels
     *
     * @return \Package\TaskBundle\Entity\Labels
     */
    public function getLabels()
    {
        return $this->labels;
    }
}