<?php
namespace Architect\ORM\src;

use \Doctrine\Common\Collections\ArrayCollection;

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
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $project_id;

	/**
	 * The name of the project
	 * @var string
	 * @Column(type="string")
	 */
	protected $project_name;

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
	 */
	private $tasks;

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
		return $this->project_id;
	}

	/**
	 * Get the name of the project
	 * @return string
	 */
	public function getProjectName()
	{
		return $this->project_name;
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
	 * @param string $project_name
	 */
	public function setProjectName($project_name)
	{
		$this->project_name = $project_name;
	}

	/**
	 * Get the tasks associated with the project
	 * @return ArrayCollection
	 */
	public function getTasks()
	{
		return $this->tasks;
	}
}