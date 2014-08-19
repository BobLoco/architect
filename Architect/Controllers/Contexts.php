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
	public function read($id = 0)
	{
		if (!empty($id)) {
			$context = $this->_orm->find('\Architect\ORM\src\Context', $id);

			if (empty($context)) {
				return new Result(ResponseCode::RESOURCE_NOT_FOUND);
			}

			return new Result(ResponseCode::OK, array(
				'context_id' => $context->getId(),
				'context_name' => $context->getContextName(),
			));
		} else {
			$repository = $this->_orm->getRepository('\Architect\ORM\src\Context');
			$contexts = $repository->findAll();

			$result = array();

			foreach ($contexts as $context) {
				$result[] = array(
					'context_id' => $context->getId(),
					'context_name' => $context->getContextName(),
				);
			}

			return new Result(ResponseCode::OK, $result);
		}
	}

	/**
	 * Create a new context
	 * @return array
	 */
	public function create()
	{
		$context = new Context();
		$context->setName(Core::$app->request->post('context_name'));

		$this->_orm->persist($context);
		$this->_orm->flush();

		Core::$app->response->headers->set('Location', Core::$app->request->getPath() . '/' . $context->getId());

		return new Result(
			ResponseCode::OK,
			array(
				'context_id' => $context->getId(),
				'context_name' => $context->getContextName(),
			)
		);
	}

	/**
	 * Update a context
	 * @param  int $id
	 * @return array
	 */
	public function update($id)
	{
		$context = $this->_orm->find('\Architect\ORM\src\Context', $id);

		if (empty($context)) {
			return new Result(ResponseCode::RESOURCE_NOT_FOUND);
		}

		$context->setContextName(Core::$app->request->put('context_name'));
		$this->_orm->persist($context);
		$this->_orm->flush();

		return new Result(
			ResponseCode::OK,
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
		$context = $this->_orm->find('\Architect\ORM\src\Context', $id);

		if (empty($context)) {
			return new Result(ResponseCode::RESOURCE_NOT_FOUND);
		}

		$this->_orm->remove($context);
		$this->_orm->flush();

		return new Result(
			ResponseCode::OK,
			array(
				'success' => true,
			)
		);
	}
}