<?php
namespace Architect\Controllers;

use \Architect\Core;
use \Architect\ORM\src\Context;
use \Architect\ResponseCode;
use \Architect\Result;

/**
 * Architect\Controllers\Context
 *
 * Contexts controler
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
	 * @param  int $id
	 * @return array
	 */
	public function read($id = null)
	{
		if (!empty($id)) {
			$context = $this->orm->find('\Architect\ORM\src\Context', $id);

			if (empty($context)) {
				return new Result(array('message' => 'Context not found'), ResponseCode::ERROR_NOTFOUND);
			}

			return new Result(array(
				'context_id' => $context->getId(),
				'context_name' => $context->getContextName(),
				'tasks' => $this->_returnTasks($context->getTasks()),
			));
		} else {
			$repository = $this->orm->getRepository('\Architect\ORM\src\Context');
			$contexts = $repository->findAll();

			$result = array();

			foreach ($contexts as $context) {
				$result[] = array(
					'context_id' => $context->getId(),
					'context_name' => $context->getContextName(),
				);
			}

			return new Result($result);
		}
	}

	/**
	 * Create a new context
	 * @return array
	 */
	public function create()
	{
		$context = new Context();
		$context->setContextName($this->container['request']->get('context_name'));

		$this->orm->persist($context);
		$this->orm->flush();

		Core::$app->response->headers->set('Location', Core::$app->request->getPath() . '/' . $context->getId());

		return new Result(
			array(
				'context_id' => $context->getId(),
				'context_name' => $context->getContextName(),
			),
			ResponseCode::OK_CREATED
		);
	}

	/**
	 * Update a context
	 * @param  int $id
	 * @return array
	 */
	public function update($id)
	{
		$context = $this->orm->find('\Architect\ORM\src\Context', $id);

		if (empty($context)) {
			return new Result(array('message' => 'Context not found'), ResponseCode::ERROR_NOTFOUND);
		}

		$context->setContextName($this->container['request']->get('context_name'));
		$this->orm->persist($context);
		$this->orm->flush();

		return new Result(
			array(
				'context_id' => $context->getId(),
				'context_name' => $context->getContextName(),
			)
		);
	}

	/**
	 * Delete a context
	 * @param  int $id
	 * @return array
	 */
	public function delete($id)
	{
		$context = $this->orm->find('\Architect\ORM\src\Context', $id);

		if (empty($context)) {
			return new Result(array('message' => 'Context not found'), ResponseCode::ERROR_NOTFOUND);
		}

		$this->orm->remove($context);
		$this->orm->flush();

		return new Result(
			array(
				'success' => true,
			)
		);
	}
}