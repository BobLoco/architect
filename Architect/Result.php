<?php
namespace Architect;

use \Pimple\Container;

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
    private $code;
    private $data = null;
    private $message = null;

    /**
     * Constructor
     * @param int $code The response code
     * @param array $data The data to be output
     * @param string $message An optional debug message to be passed
     */
    public function __construct(Container $container)
    {
        $this->code = $container['response_code']::OK;
    }

    /**
     * Set the response code
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = (int) $code;
    }

    /**
     * Get the response code
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the data of the response
     * @param array $data
     * @throws \LogicException
     */
    public function setData($data)
    {
        if (!is_array($data)) {
            throw new \LogicException('Data is not an array');
        }

        $this->data = $data;
    }

    /**
     * Get the data of the response
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set any debug message
     * @param string $message
     * @throws \LogicException
     */
    public function setMessage($message)
    {
        if (!is_string($message)) {
            throw new \LogicException('Message is not a string');
        }

        $this->message = $message;
    }

    /**
     * Get any debug message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
