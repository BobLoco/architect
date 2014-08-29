<?php
namespace Architect\Controllers;

use \Architect\Core;
use \Architect\ORM\src\Project;
use \Architect\ResponseCode;
use \Architect\Result;

/**
 * Architect\Controllers\Project
 *
 * Projects controler
 *
 * @category Controllers
 * @package Architect
 * @subpackage Controllers
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Projects extends ControllerAbstract
{
	/**
	 * Read a single project or list of projects
	 * @param  int $id
	 * @return array
	 */
	public function read($id = 0)
	{
		if (!empty($id)) {
			$project = $this->_orm->find('\Architect\ORM\src\Project', $id);

			if (empty($project)) {
				return new Result(ResponseCode::RESOURCE_NOT_FOUND);
			}

			return new Result(ResponseCode::OK, array(
				'project_id' => $project->getId(),
				'project_name' => $project->getProjectName(),
				'tasks' => $this->_returnTasks($project->getTasks()),
			));
		} else {
			$repository = $this->_orm->getRepository('\Architect\ORM\src\Project');
			$projects = $repository->findAll();

			$result = array();

			foreach ($projects as $project) {
				$result[] = array(
					'project_id' => $project->getId(),
					'project_name' => $project->getProjectName(),
				);
			}

			return new Result(ResponseCode::OK, $result);
		}
	}

	/**
	 * Create a new project
	 * @return array
	 */
	public function create()
	{
		$project = new Project();
		$project->setProjectName(Core::$app->request->post('project_name'));

		$this->_orm->persist($project);
		$this->_orm->flush();

		Core::$app->response->headers->set('Location', Core::$app->request->getPath() . '/' . $project->getId());

		return new Result(
			ResponseCode::OK,
			array(
				'project_id' => $project->getId(),
				'project_name' => $project->getProjectName(),
			)
		);
	}

	/**
	 * Update a project
	 * @param  int $id
	 * @return array
	 */
	public function update($id)
	{
		$project = $this->_orm->find('\Architect\ORM\src\Project', $id);

		if (empty($project)) {
			return new Result(ResponseCode::RESOURCE_NOT_FOUND);
		}

		$project->setProjectName(Core::$app->request->put('project_name'));
		$this->_orm->persist($project);
		$this->_orm->flush();

		return new Result(
			ResponseCode::OK,
			array(
				'project_id' => $project->getId(),
				'project_name' => $project->getProjectName(),
			)
		);
	}

	/**
	 * Delete a project
	 * @param  int $id
	 * @return array
	 */
	public function delete($id)
	{
		$project = $this->_orm->find('\Architect\ORM\src\Project', $id);

		if (empty($project)) {
			return new Result(ResponseCode::RESOURCE_NOT_FOUND);
		}

		$this->_orm->remove($project);
		$this->_orm->flush();

		return new Result(
			ResponseCode::OK,
			array(
				'success' => true,
			)
		);
	}

	/**
	 * Process the tasks associated with the project
	 * @param  ArrayCollection $tasks
	 * @return array
	 */
	private function _returnTasks($tasks)
	{
		$sorted_tasks = array();

		foreach ($tasks as $task) {
			$sorted_tasks[] = array(
				'task_id' => $task->getId(),
				'task_name' => $task->getTaskName(),
				'completed' => $task->getCompleted(),
			);
		}

		return $sorted_tasks;
	}
}