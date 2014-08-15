<?php
namespace Architect\Controllers;

use \Architect\ORM\src\Task;

class Tasks extends ControllerAbstract
{
	public function read($id = false)
	{
		if (!empty($id)) {
			$task = $this->_orm->find('\Architect\ORM\src\Task', $id);

			if (empty($task)) {
				throw new Exception('Task does not exist');
			}

			return array(
				'success' => true,
				'task_id' => $task->getId(),
				'name' => $task->getName(),
			);
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

			return $result;
		}
	}

	public function create()
	{
		$task = new Task();
		$task->setName($this->_app->request->post('name'));

		$this->_orm->persist($task);
		$this->_orm->flush();

		return array(
			'success' => true,
			'task_id' => $task->getId(),
		);
	}
}