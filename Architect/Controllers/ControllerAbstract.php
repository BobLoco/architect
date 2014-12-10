<?php
namespace Architect\Controllers;

use Architect\ORM\EntityManager;
use Architect\Request;
use Pimple\Container;

/**
 * Architect\Controllers\ControllerAbstract
 *
 * Abstracted functionality for controllers
 *
 * @category Architec
 * @package Controllers
 * @subpackage Abstract
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
abstract class ControllerAbstract
{
    protected $orm;

    protected $request;

    protected $container;

    /**
     * Constructor
     * @throws \LogicException
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        if (empty($this->container['entity_manager']) ||
            !($this->container['entity_manager'] instanceof \Architect\ORM\EntityManagerInterface)
        ) {
            throw new \LogicException('No entity manager set');
        }

        $this->orm = $this->container['entity_manager']->createManager();
    }

    /**
     * Process the tasks associated with the project or context
     * @param  ArrayCollection $tasks
     * @return array
     */
    protected function returnTasks($tasks)
    {
        $sortedTasks = array();

        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                $context = $task->getContext();
                $project = $task->getProject();

                $sortedTasks[] = array(
                    'task_id' => $task->getId(),
                    'task_name' => $task->getTaskName(),
                    'completed' => $task->getCompleted(),
                    'context' => !empty($context) ? array(
                        'context_id' => $context->getId(),
                        'context_name' => $context->getContextName(),
                    ) : null,
                    'project' => !empty($project) ? array(
                        'project_id' => $project->getId(),
                        'project_name' => $project->getProjectName(),
                    ) : null,
                );
            }
        }

        return $sortedTasks;
    }
}
