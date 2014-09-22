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

			$context = $project->getContext();

			return new Result(ResponseCode::OK, array(
				'project_id' => $project->getId(),
				'project_name' => $project->getProjectName(),
				'project_description' => $project->getProjectDescription(),
				'context' => !empty($context) ? $context : false,
				'tasks' => $this->_returnTasks($project->getTasks()),
				'created' => $project->getCreated(),
				'updated' => $project->getUpdated(),
			));
		} else {
			$repository = $this->_orm->getRepository('\Architect\ORM\src\Project');
			$projects = $repository->findAll();

			$result = array();

			foreach ($projects as $project) {
				$context = $project->getContext();

				$result[] = array(
					'project_id' => $project->getId(),
					'project_name' => $project->getProjectName(),
					'project_description' => $project->getProjectDescription(),
					'context' => !empty($context) ? $context : false,
					'created' => $project->getCreated(),
					'updated' => $project->getUpdated(),
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
		$project->setProjectDescription(Core::$app->request->post('project_description'));
		$context_id = Core::$app->request->post('context_id');

		if (!empty($context_id)) {
			$context = $this->_orm->find('\Architect\ORM\src\Context', $context_id);
		} else {
			$context = null;
		}

		$project->setContext($context);
		$project->setCreated();
		$project->setUpdated();

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

		$context_id = Core::$app->request->put('context_id');

		if (!empty($context_id)) {
			$context = $this->_orm->find('\Architect\ORM\src\Context', $context_id);
		} else {
			$context = null;
		}

		$project->setContext($context);
		$project->setUpdated();

		$project->setProjectName(Core::$app->request->put('project_name'));
		$project->setProjectDescription(Core::$app->request->put('project_description'));
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
}