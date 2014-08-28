<?php
namespace Architect\ORM\src;

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="context")
 */
class Context
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $context_id;

	/** @Column(type="string") **/
	protected $context_name;

	/**
	 * @OneToMany(targetEntity="Task", mappedBy="context")
	 */
	private $tasks;

	public function __construct()
	{
		$this->tasks = new ArrayCollection;
	}

	public function getId()
	{
		return $this->context_id;
	}

	public function getContextName()
	{
		return $this->context_name;
	}

	public function setContextName($context_name)
	{
		return $this->context_name = $context_name;
	}

	public function getTasks()
	{
		return $this->tasks;
	}
}