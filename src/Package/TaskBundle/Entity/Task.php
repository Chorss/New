<?php

namespace Package\TaskBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\Length(max=500)
     *
     * @ORM\Column(type="string", length=500)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Package\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     */
    private $assignee;

    /**
     * @ORM\ManyToOne(targetEntity="Package\TaskBundle\Entity\Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Package\TaskBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="Package\TaskBundle\Entity\Priority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     */
    private $priority;

    /**
     * @Assert\NotNull()
     *
     * @ORM\OneToMany(targetEntity="Package\TaskBundle\Entity\Worklog", mappedBy="task")
     */
    private $worklog;

    /**
     * @ORM\OneToMany(targetEntity="Package\TaskBundle\Entity\Comment", mappedBy="task")
     */
    private $comment;

    /**
     * @Assert\DateTime()
     * @Assert\NotNull()
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @Assert\DateTime()
     * @Assert\NotNull()
     *
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->comment = new ArrayCollection();
        $this->worklog = new ArrayCollection();
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
     * Set description
     *
     * @param string $description
     *
     * @return Task
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
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Task
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set assignee
     *
     * @param \Package\UserBundle\Entity\User $assignee
     *
     * @return Task
     */
    public function setAssignee(\Package\UserBundle\Entity\User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \Package\UserBundle\Entity\User
     */
    public function getAssignee()
    {
        return $this->assignee;
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
     * Set project
     *
     * @param \Package\TaskBundle\Entity\Project $project
     *
     * @return Task
     */
    public function setProject(\Package\TaskBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Package\TaskBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
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
     * Add comment
     *
     * @param \Package\TaskBundle\Entity\Comment $comment
     *
     * @return Task
     */
    public function addComment(\Package\TaskBundle\Entity\Comment $comment)
    {
        $this->comment[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \Package\TaskBundle\Entity\Comment $comment
     */
    public function removeComment(\Package\TaskBundle\Entity\Comment $comment)
    {
        $this->comment->removeElement($comment);
    }

    /**
     * Get comment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Add worklog
     *
     * @param \Package\TaskBundle\Entity\Worklog $worklog
     *
     * @return Task
     */
    public function addWorklog(\Package\TaskBundle\Entity\Worklog $worklog)
    {
        $this->worklog[] = $worklog;

        return $this;
    }

    /**
     * Remove worklog
     *
     * @param \Package\TaskBundle\Entity\Worklog $worklog
     */
    public function removeWorklog(\Package\TaskBundle\Entity\Worklog $worklog)
    {
        $this->worklog->removeElement($worklog);
    }

    /**
     * Get worklog
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorklog()
    {
        return $this->worklog;
    }
}
