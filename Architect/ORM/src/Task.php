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
 * @Table(name="tasks")
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
	protected $task_id;

	/**
	 * The name of the task
	 * @var string
	 * @Column(type="string")
	 */
	protected $task_name;

	/**
	 * The datetime the task was completed
	 * @var DateTime
	 * @Column(type="datetime", nullable=true)
	 */
	protected $completed;

	/**
	 * The context of the class
	 * @var Architect\Orm\src\Context
	 * @ManyToOne(targetEntity="Context", inversedBy="tasks")
	 * @JoinColumn(name="context_id", referencedColumnName="context_id")
	 */
	private $context;

	/**
	 * Get the ID of the task
	 * @return int
	 */
	public function getId()
	{
		return $this->task_id;
	}

	/**
	 * Get the name of the task
	 * @return string
	 */
	public function getTaskName()
	{
		return $this->task_name;
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
	 * Get the date the task was completed
	 * @return DateTime
	 */
	public function getCompleted()
	{
		return $this->completed;
	}

	/**
	 * Set the task name
	 * @param string $task_name
	 */
	public function setTaskName($task_name)
	{
		$this->task_name = $task_name;
	}

	/**
	 * Set the date the task was completed
	 * @param string $completed
	 */
	public function setCompleted($completed)
	{
		$this->completed = new \DateTime($completed);
	}
}