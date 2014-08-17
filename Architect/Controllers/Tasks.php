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

			return new Result(ResponseCode::OK, array(
				'task_id' => $task->getId(),
				'name' => $task->getName(),
			));
		} else {
			$repository = $this->_orm->getRepository('\Architect\ORM\src\Task');
			$tasks = $repository->findAll();

			$result = array();

			foreach ($tasks as $task) {
				$result[] = array(
					'task_id' => $task->getId(),
					'name' => $task->getName(),
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
		$task->setName(Core::$app->request->post('name'));

		$this->_orm->persist($task);
		$this->_orm->flush();

		Core::$app->response->headers->set('Location', Core::$app->request->getPath() . '/' . $task->getId());

		return new Result(
			ResponseCode::OK,
			array(
				'task_id' => $task->getId(),
				'name' => $task->getName(),
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

		$task->setName(Core::$app->request->put('name'));
		$this->_orm->persist($task);
		$this->_orm->flush();

		return new Result(
			ResponseCode::OK,
			array(
				'task_id' => $task->getId(),
				'name' => $task->getName(),
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