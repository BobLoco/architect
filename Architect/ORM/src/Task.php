<?php
namespace Architect\ORM\src;

/**
 * @Entity @Table(name="tasks")
 */
class Task
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $task_id;

	/** @Column(type="string") **/
	protected $task_name;

	/** @Column(type="datetime", nullable=true) **/
	protected $completed;

	/**
	 * @ManyToOne(targetEntity="Context", inversedBy="tasks")
	 * @JoinColumn(name="context_id", referencedColumnName="context_id")
	 */
	private $context;

	public function getId()
	{
		return $this->task_id;
	}

	public function getTaskName()
	{
		return $this->task_name;
	}

	public function setTaskName($task_name)
	{
		$this->task_name = $task_name;
	}

	public function getContext()
	{
		return $this->context;
	}
}