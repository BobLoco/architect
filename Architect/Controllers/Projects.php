<?php
namespace Architect\Controllers;

use Architect\Core;
use Architect\ORM\src\Project;
use Architect\ResponseCode;
use Architect\Result;
use Architect\Request;

/**
 * Architect\Controllers\Project
 *
 * Projects controler
 *
 * @category Architect
 * @package Controllers
 * @subpackage Projects
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Projects extends ControllerAbstract
{
	/**
	 * Read a single project or list of projects
	 * @param  int $project_id
	 * @return array
	 */
	public function read($project_id = null)
	{
		if (!empty($project_id)) {
			$project = $this->orm->find('\Architect\ORM\src\Project', $project_id);

			if (empty($project)) {
				return new Result(array('message' => 'Project not found'), ResponseCode::ERROR_NOTFOUND);
			}

			$context = $project->getContext();

			return new Result(array(
				'project_id' => $project->getId(),
				'project_name' => $project->getProjectName(),
				'project_description' => $project->getProjectDescription(),
				'context' => !empty($context) ? $context : false,
				'tasks' => $this->_returnTasks($project->getTasks()),
				'created' => $project->getCreated(),
				'updated' => $project->getUpdated(),
			));
		} else {
			$repository = $this->orm->getRepository('\Architect\ORM\src\Project');
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

			return new Result($result);
		}
	}

	/**
	 * Create a new project
	 * @return array
	 */
	public function create()
	{
		$project = new Project();
		$project->setProjectName($this->container['request']->get('project_name'));
		$project->setProjectDescription($this->container['request']->get('project_description'));
		$context_id = $this->container['request']->get('context_id');

		if (!empty($context_id)) {
			$context = $this->orm->find('\Architect\ORM\src\Context', $context_id);
		} else {
			$context = null;
		}

		$project->setContext($context);
		$project->setCreated();
		$project->setUpdated();

		$this->orm->persist($project);
		$this->orm->flush();

		Core::$app->response->headers->set('Location', Core::$app->request->getPath() . '/' . $project->getId());

		return new Result(
			array(
				'project_id' => $project->getId(),
				'project_name' => $project->getProjectName(),
			),
			ResponseCode::OK_CREATED
		);
	}

	/**
	 * Update a project
	 * @param  int $project_id
	 * @return array
	 */
	public function update($project_id)
	{
		$project = $this->orm->find('\Architect\ORM\src\Project', $project_id);

		if (empty($project)) {
			return new Result(array('message' => 'Context not found'), ResponseCode::ERROR_NOTFOUND);
		}

		$context_id = $this->container['request']->get('context_id');

		if (!empty($context_id)) {
			$context = $this->orm->find('\Architect\ORM\src\Context', $context_id);
		} else {
			$context = null;
		}

		$project->setContext($context);
		$project->setUpdated();

		$project->setProjectName($this->container['request']->get('project_name'));
		$project->setProjectDescription($this->container['request']->get('project_description'));
		$this->orm->persist($project);
		$this->orm->flush();

		return new Result(
			array(
				'project_id' => $project->getId(),
				'project_name' => $project->getProjectName(),
			)
		);
	}

	/**
	 * Delete a project
	 * @param  int $project_id
	 * @return array
	 */
	public function delete($project_id)
	{
		$project = $this->orm->find('\Architect\ORM\src\Project', $project_id);

		if (empty($project)) {
			return new Result(array('message' => 'Project not found'), ResponseCode::ERROR_NOTFOUND);
		}

		$this->orm->remove($project);
		$this->orm->flush();

		return new Result(
			array(
				'success' => true,
			)
		);
	}
}
