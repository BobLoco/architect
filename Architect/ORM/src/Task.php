<?php
/**
 * @Entity @Table(name="tasks")
 */
class Task
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $task_id;

	/** @Column(type="string") **/
	protected $name;

	public function getId()
	{
		return $this->task_id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
}