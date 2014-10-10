<?php
namespace Architect\Controllers;

use \Architect\ORM\EntityManager;
use \Architect\Request;

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
	protected $_orm;

	protected $_request;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$entity_manager = new EntityManager();
		$this->_orm = $entity_manager->createManager();
		$this->_request = new Request();
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