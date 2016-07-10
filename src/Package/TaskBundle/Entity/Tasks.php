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

    //onDelete="SET NULL"
    /**
     * @Assert\NotNull()
     *
     * @ORM\ManyToOne(targetEntity="Package\UserBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

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
     * @ORM\JoinColumn(name="labels_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $labels;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }
}