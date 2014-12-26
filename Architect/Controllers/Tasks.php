<?php
/**
 * Tasks controller file
 *
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
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
     * @param int $taskId The (optional) ID of the task requested
     * @return \Architect\Result The task(s) requested
     */
    public function read($taskId = null)
    {
        $container = $this->container;

        if (!empty($taskId)) {
            $task = $this->orm->find('\Architect\ORM\src\Task', $taskId);

            if (empty($task)) {
                $container['result']->setData(array('message' => 'Task not found'));
                $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);
                return $container['result'];
            }

            $formatted = $this->getTaskDetails($task);
            $container['result']->setData($formatted);

            return $container['result'];
        }

        $repository = $this->orm->getRepository('\Architect\ORM\src\Task');
        $tasks = $repository->findAll();

        $result = array();

        foreach ($tasks as $task) {
            $result[] = $this->getTaskDetails($task);
        }

        $container['result']->setData($result);

        return $container['result'];
    }

    /**
     * Create a new task
     * @return \Architect\Result The created task
     */
    public function create()
    {
        $container = $this->container;

        $task = $container['task'];
        $task->setTaskName($this->container['request']->get('task_name'));
        $task->setDue($this->container['request']->get('due'));
        $task->setCompleted($this->container['request']->get('completed'));

        $this->setContext($task);
        $this->setProject($task);

        $this->orm->persist($task);
        $this->orm->flush();

        $container['slim']->response->headers->set(
            'Location',
            $container['slim']->request->getPath() . '/' . $task->getId()
        );

        $formatted = $this->getTaskDetails($task);

        $container['result']->setData($formatted);
        $container['result']->setCode($container['response_code']::OK_CREATED);

        return $container['result'];
    }

    /**
     * Update a task
     * @param int $taskId The ID of the task to update
     * @return \Architect\Result The updated task
     */
    public function update($taskId)
    {
        $container = $this->container;

        $task = $this->orm->find('\Architect\ORM\src\Task', $taskId);

        if (empty($task)) {
            $container['result']->setData(array('message' => 'Task not found'));
            $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);
            return $container['result'];
        }

        $this->setContext($task);
        $this->setProject($task);

        $task->setTaskName($container['request']->get('task_name'));

        $task->setDue($container['request']->get('due'));
        $task->setCompleted($container['request']->get('completed'));
        $this->orm->persist($task);
        $this->orm->flush();

        $formatted = $this->getTaskDetails($task);

        $container['result']->setData($formatted);

        return $container['result'];
    }

    /**
     * Delete a task
     * @param  int $taskId The ID of the task to delete
     * @return \Architect\Result Whether the deletion was successful
     */
    public function delete($taskId)
    {
        $container = $this->container;

        $task = $this->orm->find('\Architect\ORM\src\Task', $taskId);

        if (empty($task)) {
            $container['result']->setData(array('message' => 'Task not found'));
            $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);
            return $container['result'];
        }

        $this->orm->remove($task);
        $this->orm->flush();

        $container['result']->setData(
            array(
                'success' => true,
            )
        );

        return $container['result'];
    }

    /**
     * Get the details of the task
     * @param array $task The task
     * @return array The formatted task
     */
    private function getTaskDetails($task)
    {
        $completed = $task->getCompleted();
        $due = $task->getDue();
        $context = $task->getContext();
        $project = $task->getProject();

        return array(
            'task_id' => $task->getId(),
            'task_name' => $task->getTaskName(),
            'context' => !empty($context) ? $this->returnContext($context) : false,
            'project' => !empty($project) ? $this->returnProject($project) : false,
            'due' => !empty($due) ? $due : false,
            'completed' => !empty($completed) ? $completed : false,
        );
    }

    /**
     * Format a context to return
     * @param  Architect\ORM\src\Context $context The context object to return
     * @return array The context ID and name
     */
    private function returnContext($context)
    {
        return array(
            'context_id' => $context->getId(),
            'context_name' => $context->getContextName(),
        );
    }

    /**
     * Format a project to return
     * @param  Architect\ORM\src\Project $project The project object to return
     * @return array The project ID and name
     */
    private function returnProject($project)
    {
        return array(
            'project_id' => $project->getId(),
            'project_name' => $project->getProjectName(),
        );
    }

    /**
     * Set the context of the task
     * @param Architect\ORM\src\Task $task The task object
     */
    private function setContext($task)
    {
        $contextId = $this->container['request']->get('context_id');

        $context = null;

        if (!empty($contextId)) {
            $context = $this->orm->find('\Architect\ORM\src\Context', $contextId);
        }

        $task->setContext($context);
    }

    /**
     * Set the project of the task
     * @param int $projectId The ID of the project to associate
     * @param Architect\ORM\src\Task $task The task object
     */
    private function setProject($projectId, $task)
    {
        $projectId = $this->container['request']->get('project_id');

        $project = null;

        if (!empty($projectId)) {
            $project = $this->orm->find('\Architect\ORM\src\Project', $projectId);
        }

        $task->setProject($project);
    }
}
