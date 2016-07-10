<?php

namespace Package\TaskBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="labels")
 */
class Labels
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
     *
     * @ORM\OneToMany(targetEntity="Tasks", mappedBy="labels")
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }
    
}