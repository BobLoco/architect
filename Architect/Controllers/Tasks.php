<?php
namespace Architect\Controllers;

use Architect\Core;
use Architect\ORM\src\Task;
use Architect\ResponseCode;
use Architect\Result;

/**
 * Architect\Controllers\Task
 *
 * Tasks controller
 *
 * @category Controllers
 * @package Architect
 * @subpackage Controllers
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Tasks extends ControllerAbstract
{
	/**
	 * Read a single task or list of tasks
	 * @param  int $task_id
	 * @return array
	 */
	public function read($task_id = null)
	{
		if (!empty($task_id)) {
			$task = $this->orm->find('\Architect\ORM\src\Task', $task_id);

			if (empty($task)) {
				return new Result(array('message' => 'Task not found'), ResponseCode::ERROR_NOTFOUND);
			}

			$completed = $task->getCompleted();
			$due = $task->getDue();
			$context = $task->getContext();
			$project = $task->getProject();

			return new Result(array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'context' => !empty($context) ? $this->_returnContext($context) : false,
				'project' => !empty($project) ? $this->_returnProject($project) : false,
				'due' => !empty($due) ? $due : false,
				'completed' => !empty($completed) ? $completed : false,
			));
		} else {
			$repository = $this->orm->getRepository('\Architect\ORM\src\Task');
			$tasks = $repository->findAll();

			$result = array();

			foreach ($tasks as $task) {
				$completed = $task->getCompleted();
				$due = $task->getDue();
				$context = $task->getContext();
				$project = $task->getProject();

				$result[] = array(
					'task_id' => $task->getId(),
					'task_name' => $task->getTaskName(),
					'context' => !empty($context) ? $this->_returnContext($context) : false,
					'project' => !empty($project) ? $this->_returnProject($project) : false,
					'due' => !empty($due) ? $due : false,
					'completed' => !empty($completed) ? $completed : false,
				);
			}

			return new Result($result);
		}
	}

	/**
	 * Create a new task
	 * @return array
	 */
	public function create()
	{
		$task = new Task();
		$task->setTaskName($this->container['request']->get('task_name'));
		$task->setDue($this->container['request']->get('due'));
		$task->setCompleted($this->container['request']->get('completed'));

		$context_id = $this->container['request']->get('context_id');
		$project_id = $this->container['request']->get('project_id');

		if (!empty($context_id)) {
			$context = $this->orm->find('\Architect\ORM\src\Context', $context_id);
			$task->setContext($context);
		}

		if (!empty($project_id)) {
			$project = $this->orm->find('\Architect\ORM\src\Project', $project_id);
			$task->setProject($project);
		}

		$this->orm->persist($task);
		$this->orm->flush();

		Core::$app->response->headers->set('Location', Core::$app->request->getPath() . '/' . $task->getId());

		$due = $task->getDue();
		$completed = $task->getCompleted();

		return new Result(
			array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'context' => !empty($context) ? $this->_returnContext($context) : false,
				'due' => !empty($due) ? $due : false,
				'completed' => !empty($completed) ? $completed : false,
			),
			ResponseCode::OK_CREATED
		);
	}

	/**
	 * Update a task
	 * @param  int $task_id
	 * @return array
	 */
	public function update($task_id)
	{
		$task = $this->orm->find('\Architect\ORM\src\Task', $task_id);

		if (empty($task)) {
			return new Result(array('message' => 'Context not found'), ResponseCode::ERROR_NOTFOUND);
		}

		$context_id = $this->container['request']->get('context_id');
		$project_id = $this->container['request']->get('project_id');

		if (!empty($context_id)) {
			$context = $this->orm->find('\Architect\ORM\src\Context', $context_id);
			$task->setContext($context);
		} else {
			$task->setContext(null);
		}

		if (!empty($project_id)) {
			$project = $this->orm->find('\Architect\ORM\src\Project', $project_id);
			$task->setProject($project);
		} else {
			$task->setProject(null);
		}

		$task->setTaskName($this->container['request']->get('task_name'));

		$task->setDue($this->container['request']->get('due'));
		$task->setCompleted($this->container['request']->get('completed'));
		$this->orm->persist($task);
		$this->orm->flush();

		$due = $task->getDue();
		$completed = $task->getCompleted();
		$context = $task->getContext();
		$project = $task->getProject();

		return new Result(
						array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'context' => !empty($context) ? $this->_returnContext($context) : false,
				'project' => !empty($project) ? $this->_returnProject($project) : false,
				'due' => !empty($due) ? $due : false,
				'completed' => !empty($completed) ? $completed : false,
			)
		);
	}

	/**
	 * Delete a task
	 * @param  int $task_id
	 * @return array
	 */
	public function delete($task_id)
	{
		$task = $this->orm->find('\Architect\ORM\src\Task', $task_id);

		if (empty($task)) {
			return new Result(array('message' => 'Task not found'), ResponseCode::ERROR_NOTFOUND);
		}

		$this->orm->remove($task);
		$this->orm->flush();

		return new Result(
			array(
				'success' => true,
			)
		);
	}

	/**
	 * Format a context to return
	 * @param  Architect\ORM\src\Context $context
	 * @return array
	 */
	private function _returnContext($context)
	{
		return array(
			'context_id' => $context->getId(),
			'context_name' => $context->getContextName(),
		);
	}

	/**
	 * Format a project to return
	 * @param  Architect\ORM\src\Project $project
	 * @return array
	 */
	private function _returnProject($project)
	{
		return array(
			'project_id' => $project->getId(),
			'project_name' => $project->getProjectName(),
		);
	}
}