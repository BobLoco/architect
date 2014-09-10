<?php
namespace Architect\Controllers;

use \Architect\ORM\EntityManager;

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

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$entity_manager = new EntityManager();
		$this->_orm = $entity_manager->createManager();
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
				$sorted_tasks[] = array(
					'task_id' => $task->getId(),
					'task_name' => $task->getTaskName(),
					'completed' => $task->getCompleted(),
				);
			}
		}

		return $sorted_tasks;
	}
}