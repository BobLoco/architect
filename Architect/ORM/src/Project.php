<?php
/**
 * ORM project object definition file
 *
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
namespace Architect\ORM\src;

use \Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

/**
 * Architect\ORM\src\Project
 *
 * Project object for ORM
 *
 * @category ORM
 * @package Architect
 * @subpackage ORM
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 * @Entity
 * @Table(name="project")
 */
class Project
{
    /**
     * The ID of the project
     * @var int
     * @Id
     * @Column(type="integer", name="project_id")
     * @GeneratedValue
     */
    protected $projectId;

    /**
     * The name of the project
     * @var string
     * @Column(type="string", name="project_name")
     */
    protected $projectName;

    /**
     * The description / goal of the project
     * @var string
     * @Column(type="string", nullable=true, name="project_description")
     */
    protected $projectDescription;

    /**
     * The context of the task
     * @var Architect\Orm\src\Context
     * @ManyToOne(targetEntity="Context", inversedBy="task")
     * @JoinColumn(name="context_id", referencedColumnName="context_id")
     */
    private $context;

    /**
     * Tasks attached to the project
     * @var ArrayCollection
     * @OneToMany(targetEntity="Task", mappedBy="project")
     * @OrderBy({"completed" = "ASC", "order" = "ASC"})
     */
    private $tasks;

    /**
     * Created timestamp
     * @var DateTime
     * @Column(type="datetime", name="created")
     */
    private $created;

    /**
     * Updated timestamp
     * @var DateTime
     * @Column(type="datetime", name="updated")
     */
    private $updated;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection;
    }

    /**
     * Get the ID of the project
     * @return int
     */
    public function getId()
    {
        return $this->projectId;
    }

    /**
     * Get the name of the project
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Get the description of the project
     * @return string
     */
    public function getProjectDescription()
    {
        return $this->projectDescription;
    }

    /**
     * Get the context of the class
     * @return Architect\Orm\src\Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set the context of the task
     * @param Architect\Orm\src\Context $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * Set the name of the project
     * @param string $projectName
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
    }

    /**
     * Set the description of the project
     * @param string $projectDescription
     */
    public function setProjectDescription($projectDescription)
    {
        $this->projectDescription = $projectDescription;
    }

    /**
     * Get the tasks associated with the project
     * @return ArrayCollection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set the updated date
     */
    public function setUpdated()
    {
        $this->updated = new DateTime();
    }

    /**
     * Set the created date
     */
    public function setCreated()
    {
        $this->created = new DateTime();
    }

    /**
     * Get the updated date
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get the created date
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
}
