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
    private $code;
    private $data;
    private $message;

    /**
     * Constructor
     * @param int $code The response code
     * @param array $data The data to be output
     * @param string $message An optional debug message to be passed
     */
    public function __construct($data = null, $code = ResponseCode::OK, $message = null)
    {
        $this->code = $code;
        $this->data = $data;
        $this->message = $message;
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
     * Get the data of the response
     * @return array
     */
    public function getData()
    {
        return $this->data;
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