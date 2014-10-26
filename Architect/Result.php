<?php
namespace Architect;

/**
 * Architect\Result
 *
 * A result to be passed to the output
 *
 * @category Core
 * @package Architect
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Result extends ArchitectAbstract
{
	private $_code;
	private $_data;
	private $_message;

	/**
	 * Constructor
	 * @param int $code The response code
	 * @param array $data The data to be output
	 * @param string $message An optional debug message to be passed
	 */
	public function __construct($data = null, $code = ResponseCode::OK, $message = null)
	{
		$this->_code = $code;
		$this->_data = $data;
		$this->_message = $message;
	}

	/**
	 * Get the response code
	 * @return int
	 */
	public function getCode()
	{
		return $this->_code;
	}

	/**
	 * Get the data of the response
	 * @return array
	 */
	public function getData()
	{
		return $this->_data;
	}

	/**
	 * Get any debug message
	 * @return string
	 */
	public function getMessage()
	{
		return $this->_message;
	}
}