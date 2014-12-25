<?php
/**
 * Contexts controller file
 */
namespace Architect\Controllers;

use \Architect\Core;
use \Architect\ORM\src\Context;
use \Architect\ResponseCode;
use \Architect\Result;

/**
 * Architect\Controllers\Context
 *
 * Contexts controller
 *
 * @category Controllers
 * @package Architect
 * @subpackage Controllers
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Contexts extends ControllerAbstract
{
    /**
     * Read a single context or list of contexts
     * @param  int $contextId
     * @return array
     */
    public function read($contextId = null)
    {
        $container = $this->container;

        if (!empty($contextId)) {
            $context = $this->orm->find('\Architect\ORM\src\Context', $contextId);

            if (empty($context)) {
                $container['result']->setData(array('message' => 'Context not found'));
                $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);
                return $container['result'];
            }

            $container['result']->setData(array(
                'context_id' => $context->getId(),
                'context_name' => $context->getContextName(),
                'tasks' => $this->returnTasks($context->getTasks()),
            ));
            return $container['result'];
        }

        $repository = $this->orm->getRepository('\Architect\ORM\src\Context');
        $contexts = $repository->findAll();

        $result = array();

        foreach ($contexts as $context) {
            $result[] = array(
                'context_id' => $context->getId(),
                'context_name' => $context->getContextName(),
            );
        }

        $container['result']->setData($result);

        return $container['result'];
    }

    /**
     * Create a new context
     * @return array
     */
    public function create()
    {
        $container = $this->container;
        $context = $container['context'];
        $context->setContextName($container['request']->get('context_name'));

        $this->orm->persist($context);
        $this->orm->flush();

        $container['slim']->response->headers->set(
            'Location',
            $container['slim']->request->getPath() . '/' . $context->getId()
        );

        $container['result']->setCode($container['response_code']::OK_CREATED);
        $container['result']->setData(
            array(
                'context_id' => $context->getId(),
                'context_name' => $context->getContextName(),
            )
        );

        return $container['result'];
    }

    /**
     * Update a context
     * @param  int $contextId
     * @return array
     */
    public function update($contextId)
    {
        $container = $this->container;
        $context = $this->orm->find('\Architect\ORM\src\Context', $contextId);

        if (empty($context)) {
            $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);
            $container['result']->setData(array('message' => 'Context not found'));

            return $container['result'];
        }

        $context->setContextName($container['request']->get('context_name'));
        $this->orm->persist($context);
        $this->orm->flush();

        $container['result']->setData(
            array(
                'context_id' => $context->getId(),
                'context_name' => $context->getContextName(),
            )
        );

        return $container['result'];
    }

    /**
     * Delete a context
     * @param  int $contextId
     * @return array
     */
    public function delete($contextId)
    {
        $container = $this->container;
        $context = $this->orm->find('\Architect\ORM\src\Context', $contextId);

        if (empty($context)) {
            $container['result']->setCode($container['response_code']::ERROR_NOTFOUND);
            $container['result']->setData(array('message' => 'Context not found'));
            return $container['result'];
        }

        $this->orm->remove($context);
        $this->orm->flush();

        $container['result']->setData(
            array(
                'success' => true,
            )
        );
        return $container['result'];
    }
}
