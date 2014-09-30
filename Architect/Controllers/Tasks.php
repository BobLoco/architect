<?php
namespace Architect\Controllers;

use Architect\Core;
use Architect\ORM\src\Task;
use Architect\ResponseCode;
use Architect\Result;

/**
 * Architect\Controllers\Task
 *
 * Tasks controler
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
	public function read($task_id = 0)
	{
		if (!empty($task_id)) {
			$task = $this->_orm->find('\Architect\ORM\src\Task', $task_id);

			if (empty($task)) {
				return new Result(ResponseCode::RESOURCE_NOT_FOUND);
			}

			$completed = $task->getCompleted();
			$due = $task->getDue();
			$context = $task->getContext();
			$project = $task->getProject();

			return new Result(ResponseCode::OK, array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'context' => !empty($context) ? $this->_returnContext($context) : false,
				'project' => !empty($project) ? $this->_returnProject($project) : false,
				'due' => !empty($due) ? $due : false,
				'completed' => !empty($completed) ? $completed : false,
			));
		} else {
			$repository = $this->_orm->getRepository('\Architect\ORM\src\Task');
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

			return new Result(ResponseCode::OK, $result);
		}
	}

	/**
	 * Create a new task
	 * @return array
	 */
	public function create()
	{
		$task = new Task();
		$task->setTaskName(Core::$app->request->post('task_name'));
		$task->setDue(Core::$app->request->post('due'));
		$task->setCompleted(Core::$app->request->post('completed'));

		$context_id = Core::$app->request->post('context_id');
		$project_id = Core::$app->request->post('project_id');

		if (!empty($context_id)) {
			$context = $this->_orm->find('\Architect\ORM\src\Context', $context_id);
			$task->setContext($context);
		}

		if (!empty($project_id)) {
			$project = $this->_orm->find('\Architect\ORM\src\Project', $project_id);
			$task->setProject($project);
		}

		$this->_orm->persist($task);
		$this->_orm->flush();

		Core::$app->response->headers->set('Location', Core::$app->request->getPath() . '/' . $task->getId());

		$due = $task->getDue();
		$completed = $task->getCompleted();

		return new Result(
			ResponseCode::OK,
			array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'context' => !empty($context) ? $this->_returnContext($context) : false,
				'due' => !empty($due) ? $due : false,
				'completed' => !empty($completed) ? $completed : false,
			)
		);
	}

	/**
	 * Update a task
	 * @param  int $task_id
	 * @return array
	 */
	public function update($task_id)
	{
		$task = $this->_orm->find('\Architect\ORM\src\Task', $task_id);

		if (empty($task)) {
			return new Result(ResponseCode::RESOURCE_NOT_FOUND);
		}

		$context_id = Core::$app->request->put('context_id');
		$project_id = Core::$app->request->put('project_id');

		if (!empty($context_id)) {
			$context = $this->_orm->find('\Architect\ORM\src\Context', $context_id);
			$task->setContext($context);
		} else {
			$task->setContext(null);
		}

		if (!empty($project_id)) {
			$project = $this->_orm->find('\Architect\ORM\src\Project', $project_id);
			$task->setProject($project);
		} else {
			$task->setProject(null);
		}

		$task->setTaskName(Core::$app->request->put('task_name'));

		$task->setDue(Core::$app->request->put('due'));
		$task->setCompleted(Core::$app->request->put('completed'));
		$this->_orm->persist($task);
		$this->_orm->flush();

		$due = $task->getDue();
		$completed = $task->getCompleted();
		$context = $task->getContext();
		$project = $task->getProject();

		return new Result(
			ResponseCode::OK,
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
		$task = $this->_orm->find('\Architect\ORM\src\Task', $task_id);

		if (empty($task)) {
			return new Result(ResponseCode::RESOURCE_NOT_FOUND);
		}

		$this->_orm->remove($task);
		$this->_orm->flush();

		return new Result(
			ResponseCode::OK,
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