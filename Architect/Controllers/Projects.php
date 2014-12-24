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
 * Projects controller. Allows for reading, creating, updating and deleting
 * of projects
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
     * @param  int $projectId The ID of the project to be returned
     * @return \Architect\Result The project(s) returned from the system
     */
    public function read($projectId = null)
    {
        // This is ugly, but we have to do this to prevent an
        // unexpected T_PAAMAIYM_NEKUDOTAYIM
        $container = $this->container;

        if (!empty($projectId)) {
            $project = $this->orm->find('\Architect\ORM\src\Project', $projectId);

            if (empty($project)) {
                $container['result']->setData(array('message' => 'Project not found'));
                $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);
                return $container['result'];
            }

            $context = $project->getContext();

            $container['result']->setData(array(
                'project_id' => $project->getId(),
                'project_name' => $project->getProjectName(),
                'project_description' => $project->getProjectDescription(),
                'context' => !empty($context) ? $context : false,
                'tasks' => $this->returnTasks($project->getTasks()),
                'created' => $project->getCreated(),
                'updated' => $project->getUpdated(),
            ));

            return $container['result'];
        }

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

        $container['result']->setData($result);
        return $container['result'];
    }

    /**
     * Create a new project via POST
     * @return \Architect\Result The project ID and name
     */
    public function create()
    {
        $container = $this->container;

        $project = $container['project'];

        $this->setProjectDetails($project);

        $project->setCreated();

        $container['slim']->response->headers->set(
            'Location',
            $container['slim']->request->getPath() . '/' . $project->getId()
        );

        $container['result']->setData(array(
            'project_id' => $project->getId(),
            'project_name' => $project->getProjectName(),
        ));
        $container['result']->setCode($container['response_code']::OK_CREATED);

        return $container['result'];
    }

    /**
     * Update a project by passing a JSON object via PUT
     * Updates all fields for the project, so will null any not provided
     * @param  int $projectId The ID of the project to update
     * @return \Architect\Result The project name and ID
     */
    public function update($projectId)
    {
        $container = $this->container;

        $project = $this->orm->find('\Architect\ORM\src\Project', $projectId);

        if (empty($project)) {
            $container['result']->setData(array('message' => 'Project not found'));
            $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);

            return $container['result'];
        }

        $this->setProjectDetails($project);

        $container['result']->setData(
            array(
                'project_id' => $project->getId(),
                'project_name' => $project->getProjectName(),
            )
        );

        return $container['result'];
    }

    /**
     * Delete a project via DELETE
     * @param  int $projectId The ID of the project to delete
     * @return \Architect\Result Whether the deletion was successful
     */
    public function delete($projectId)
    {
        $container = $this->container;
        $project = $this->orm->find('\Architect\ORM\src\Project', $projectId);

        if (empty($project)) {
            $container['result']->setData(array('message' => 'Context not found'));
            $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);

            return $container['result'];
        }

        $this->orm->remove($project);
        $this->orm->flush();

        $container['result']->setData(array(
            'success' => true,
        ));

        return $container['result'];
    }

    /**
     * Set the details of the project and commit them to the ORM
     * @param \Architect\ORM\src\Project $project The project object to be updated
     */
    private function setProjectDetails(Project $project)
    {
        $contextId = $this->container['request']->get('context_id');

        $context = null;

        if (!empty($contextId)) {
            $context = $this->orm->find('\Architect\ORM\src\Context', $contextId);
        }

        $project->setContext($context);
        $project->setUpdated();

        $project->setProjectName($this->container['request']->get('project_name'));
        $project->setProjectDescription(
            $this->container['request']->get('project_description')
        );
        $this->orm->persist($project);
        $this->orm->flush();
    }
}
