<?php

namespace Package\TaskBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="Package\TaskBundle\Repository\TaskRepository")
 */
class Task
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
     * @ORM\ManyToOne(targetEntity="Priority", inversedBy="task")
     * @ORM\JoinColumn(name="priorities_id", referencedColumnName="id")
     */
    private $priority;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     *
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="task")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Label", inversedBy="task")
     * @ORM\JoinColumn(name="labels_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $label;

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
     * @return Task
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
     * @return Task
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
     * @return Task
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
     * Set priority
     *
     * @param \Package\TaskBundle\Entity\Priority $priority
     *
     * @return Task
     */
    public function setPriority(\Package\TaskBundle\Entity\Priority $priority = null)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return \Package\TaskBundle\Entity\Priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param \Package\TaskBundle\Entity\Status $status
     *
     * @return Task
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
     * Set label
     *
     * @param \Package\TaskBundle\Entity\Label $label
     *
     * @return Task
     */
    public function setLabel(\Package\TaskBundle\Entity\Label $label = null)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return \Package\TaskBundle\Entity\Label
     */
    public function getLabel()
    {
        return $this->label;
    }
}
