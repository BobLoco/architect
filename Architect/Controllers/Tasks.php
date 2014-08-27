<?php
namespace Architect\Controllers;

use \Architect\Core;
use \Architect\ORM\src\Task;
use \Architect\ResponseCode;
use \Architect\Result;

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
	 * @param  int $id
	 * @return array
	 */
	public function read($id = 0)
	{
		if (!empty($id)) {
			$task = $this->_orm->find('\Architect\ORM\src\Task', $id);

			if (empty($task)) {
				return new Result(ResponseCode::RESOURCE_NOT_FOUND);
			}

			$completed = $task->getCompleted();

			return new Result(ResponseCode::OK, array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'completed' => !empty($completed) ? $completed : false,
			));
		} else {
			$repository = $this->_orm->getRepository('\Architect\ORM\src\Task');
			$tasks = $repository->findAll();

			$result = array();

			foreach ($tasks as $task) {
				$completed = $task->getCompleted();

				$result[] = array(
					'task_id' => $task->getId(),
					'task_name' => $task->getTaskName(),
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
		$task->setCompleted(Core::$app->request->post('completed'));

		$this->_orm->persist($task);
		$this->_orm->flush();

		Core::$app->response->headers->set('Location', Core::$app->request->getPath() . '/' . $task->getId());

		$completed = $task->getCompleted();

		return new Result(
			ResponseCode::OK,
			array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'completed' => !empty($completed) ? $completed : false,
			)
		);
	}

	/**
	 * Update a task
	 * @param  int $id
	 * @return array
	 */
	public function update($id)
	{
		$task = $this->_orm->find('\Architect\ORM\src\Task', $id);

		if (empty($task)) {
			return new Result(ResponseCode::RESOURCE_NOT_FOUND);
		}

		$context_id = Core::$app->request->put('context_id');

		if (!empty($context_id)) {
			$context = $this->_orm->find('\Architect\ORM\src\Context', $context_id);
			$task->setContext($context);
		}

		$task->setTaskName(Core::$app->request->put('task_name'));

		$task->setCompleted(Core::$app->request->put('completed'));
		$this->_orm->persist($task);
		$this->_orm->flush();

		$completed = $task->getCompleted();
		$context = $task->getContext();

		return new Result(
			ResponseCode::OK,
			array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'context' => !empty($context) ? $context->getContextName() : false,
				'completed' => !empty($completed) ? $completed : false,
			)
		);
	}

	/**
	 * Delete a task
	 * @param  int $id
	 * @return array
	 */
	public function delete($id)
	{
		$task = $this->_orm->find('\Architect\ORM\src\Task', $id);

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
}