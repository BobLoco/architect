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
     * @param  int $taskId
     * @return array
     */
    public function read($taskId = null)
    {
        if (!empty($taskId)) {
            $task = $this->orm->find('\Architect\ORM\src\Task', $taskId);

            if (empty($task)) {
                return new Result(array('message' => 'Task not found'), ResponseCode::ERROR_NOTFOUND);
            }

            $formatted = $this->getTaskDetails($task);

            return new Result($formatted);
        }

        $repository = $this->orm->getRepository('\Architect\ORM\src\Task');
        $tasks = $repository->findAll();

        $result = array();

        foreach ($tasks as $task) {
            $result[] = $this->getTaskDetails($task);
        }

        return new Result($result);
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

        $contextId = $this->container['request']->get('context_id');
        $projectId = $this->container['request']->get('project_id');

        if (!empty($contextId)) {
            $context = $this->orm->find('\Architect\ORM\src\Context', $contextId);
            $task->setContext($context);
        }

        if (!empty($projectId)) {
            $project = $this->orm->find('\Architect\ORM\src\Project', $projectId);
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
                'context' => !empty($context) ? $this->returnContext($context) : false,
                'due' => !empty($due) ? $due : false,
                'completed' => !empty($completed) ? $completed : false,
            ),
            ResponseCode::OK_CREATED
        );
    }

    /**
     * Update a task
     * @param  int $taskId
     * @return array
     */
    public function update($taskId)
    {
        $task = $this->orm->find('\Architect\ORM\src\Task', $taskId);

        if (empty($task)) {
            return new Result(array('message' => 'Context not found'), ResponseCode::ERROR_NOTFOUND);
        }

        $contextId = $this->container['request']->get('context_id');
        $projectId = $this->container['request']->get('project_id');

        if (!empty($contextId)) {
            $context = $this->orm->find('\Architect\ORM\src\Context', $contextId);
            $task->setContext($context);
        } else {
            $task->setContext(null);
        }

        if (!empty($projectId)) {
            $project = $this->orm->find('\Architect\ORM\src\Project', $projectId);
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
                'context' => !empty($context) ? $this->returnContext($context) : false,
                'project' => !empty($project) ? $this->returnProject($project) : false,
                'due' => !empty($due) ? $due : false,
                'completed' => !empty($completed) ? $completed : false,
            )
        );
    }

    /**
     * Delete a task
     * @param  int $taskId
     * @return array
     */
    public function delete($taskId)
    {
        $task = $this->orm->find('\Architect\ORM\src\Task', $taskId);

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
     * @param  Architect\ORM\src\Context $context
     * @return array
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
     * @param  Architect\ORM\src\Project $project
     * @return array
     */
    private function returnProject($project)
    {
        return array(
            'project_id' => $project->getId(),
            'project_name' => $project->getProjectName(),
        );
    }
}