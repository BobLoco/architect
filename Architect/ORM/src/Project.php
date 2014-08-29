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
		$this->tasks;
	}
}