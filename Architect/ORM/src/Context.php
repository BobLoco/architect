<?php
/**
 * Context ORM object definition file
 *
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
namespace Architect\ORM\src;

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Architect\ORM\src\Context
 *
 * Context object for ORM
 *
 * @category ORM
 * @package Architect
 * @subpackage ORM
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 * @Entity
 * @Table(name="context")
 */
class Context
{
    /**
     * The ID of the context
     * @var int
     * @Id
     * @Column(type="integer", name="context_id")
     * @GeneratedValue
     */
    protected $contextId;

    /**
     * The name of the context
     * @var string
     * @Column(type="string", name="context_name")
     */
    protected $contextName;

    /**
     * Tasks attached to the context
     * @OneToMany(targetEntity="Task", mappedBy="context")
     */
    private $tasks;

    /**
     * Projects attached to the context
     * @var ArrayCollection
     * @OneToMany(targetEntity="Project", mappedBy="context")
     */
    private $projects;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection;
    }

    /**
     * Get the ID of the context
     * @return int
     */
    public function getId()
    {
        return $this->contextId;
    }

    /**
     * Get the name of the context
     * @return string
     */
    public function getContextName()
    {
        return $this->contextName;
    }

    /**
     * Set the name of the context
     * @param string $contextName
     */
    public function setContextName($contextName)
    {
        $this->contextName = $contextName;
    }

    /**
     * Get the tasks associated with the context
     * @return ArrayCollection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Get the projects associated with the context
     * @return ArrayCollection
     */
    public function getProjects()
    {
        return $this->projects;
    }
}
