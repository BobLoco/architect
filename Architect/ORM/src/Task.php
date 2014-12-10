<?php
namespace Architect\ORM\src;

/**
 * Architect\ORM\src\Task
 *
 * Task object for ORM
 *
 * @category ORM
 * @package Architect
 * @subpackage ORM
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 * @Entity
 * @Table(name="task")
 */
class Task
{
    /**
     * The id of the task
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $taskId;

    /**
     * The name of the task
     * @var string
     * @Column(type="string")
     */
    protected $taskName;

    /**
     * The datetime the task is due
     * @var DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $due;

    /**
     * The datetime the task was completed
     * @var DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $completed;

    /**
     * The context of the task
     * @var Architect\Orm\src\Context
     * @ManyToOne(targetEntity="Context", inversedBy="task")
     * @JoinColumn(name="context_id", referencedColumnName="context_id")
     */
    private $context;

    /**
     * The project of the task
     * @var Architect\Orm\src\Project
     * @ManyToOne(targetEntity="Project", inversedBy="task")
     * @JoinColumn(name="project_id", referencedColumnName="project_id")
     */
    private $project;

    /**
     * The order of the task
     * @var int
     * @Column(type="integer", nullable=true)
     */
    private $order;

    /**
     * Get the ID of the task
     * @return int
     */
    public function getId()
    {
        return $this->taskId;
    }

    /**
     * Get the name of the task
     * @return string
     */
    public function getTaskName()
    {
        return $this->taskName;
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
     * Get the context of the class
     * @return Architect\Orm\src\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Get the date the task is due
     * @return DateTime
     */
    public function getDue()
    {
        return $this->due;
    }

    /**
     * Get the date the task was completed
     * @return DateTime
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Get the order of the task within the project
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the task name
     * @param string $taskName
     */
    public function setTaskName($taskName)
    {
        $this->taskName = $taskName;
    }

    /**
     * Set the date the task is due
     * @param string $completed
     */
    public function setDue($due)
    {
        if (!empty($due) && $due !== 'false') {
            $this->due = new \DateTime($due);
        } else {
            $this->due = null;
        }
    }

    /**
     * Set the date the task was completed
     * @param string $completed
     */
    public function setCompleted($completed)
    {
        if (!empty($completed) && $completed !== 'false') {
            $this->completed = new \DateTime($completed);
        } else {
            $this->completed = null;
        }
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
     * Set the project of the task
     * @param Architect\Orm\src\Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * Set the order of the task within the project
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = (int) $order;
    }
}
