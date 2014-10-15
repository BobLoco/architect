<?php
namespace Architect\Controllers;

use \Architect\ORM\EntityManager;
use \Architect\Request;
use \Pimple\Container;

/**
 * Architect\Controllers\ControllerAbstract
 *
 * Abstracted functionality for controllers
 *
 * @category Controllers
 * @package Architect
 * @subpackage Controllers
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
abstract class ControllerAbstract
{
	protected $orm;

	protected $request;

	protected $container;

	/**
	 * Constructor
	 */
	public function __construct(Container $container)
	{
		$entity_manager = new EntityManager();
		$this->orm = $entity_manager->createManager();
		$this->request = new Request();
		$this->container = $container;
	}

	/**
	 * Process the tasks associated with the project or context
	 * @param  ArrayCollection $tasks
	 * @return array
	 */
	protected function _returnTasks($tasks)
	{
		$sorted_tasks = array();

		if (!empty($tasks)) {
			foreach ($tasks as $task) {
				$context = $task->getContext();
				$project = $task->getProject();

				$sorted_tasks[] = array(
					'task_id' => $task->getId(),
					'task_name' => $task->getTaskName(),
					'completed' => $task->getCompleted(),
					'context' => array(
						'context_id' => $context->getId(),
						'context_name' => $context->getContextName(),
					),
					'project' => array(
						'project_id' => $project->getId(),
						'project_name' => $project->getProjectName(),
					),
				);
			}
		}

		return $sorted_tasks;
	}
}