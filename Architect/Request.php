<?php
namespace Architect;

use \Architect\ORM\EntityManager;
use \Architect\ResponseCode;
use \Pimple\Container;

/**
 * Architect\Request
 *
 * Security and access layer
 *
 * @category Request
 * @package Architect
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Request
{

    /**
     * Master key
     */
    const MASTER = '1392efdc4a7dd3808f33940250f624fd';

    /**
     * The request data
     * @var array
     */
    private $requestData = array();

    /**
     * DI container
     * @var Container
     */
    private $container;

    /**
     * Constructor
     */
    public function __construct(Container $container)
    {
        $this->requestData = $container['slim']->request()->getBody();
        $this->container = $container;

        // Total hack to clean-up request data
        // For some reason Slim's middleware doesn't work :-(
        if (!is_array($this->requestData)) {
            $this->requestData = json_decode($this->requestData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                parse_str($this->requestData, $this->requestData);
            }
        }
    }

    /**
     * Validate the request
     * @return boolean Whether the request is valid
     */
    public function validate()
    {
        // Grab the parameters from the request
        $params = $this->container['slim']->request()->params();

        if (empty($params['secret'])) {
            throw new \RuntimeException('No application secret set', ResponseCode::ERROR_BADREQUEST);
        }

        $secret = $params['secret'];

        if ($secret == self::MASTER) {
            $this->container['slim']->response()->header('Access-Control-Allow-Origin', '*');
            return true;
        } else {
            if (empty($params['app_id'])) {
                throw new \RuntimeException('No application ID set', ResponseCode::ERROR_BADREQUEST);
            }

            $entityManager = $this->container['entity_manager'];
            $orm = $entityManager->createManager();

            $appId = (int) $params['app_id'];

            $app = $orm->find('\Architect\ORM\src\App', $appId);

            if (empty($app)) {
                throw new \RuntimeException('Invalid credentials', ResponseCode::ERROR_AUTH);
            }

            $storedSecret = $app->getAppSecret();

            if ($params['secret'] === $storedSecret) {
                $this->container['slim']->response()->header('Access-Control-Allow-Origin', $app->getAppUrl());
                return true;
            } else {
                throw new \RuntimeException('Invalid credentials', ResponseCode::ERROR_AUTH);
            }
        }
    }

    /**
     * Retrieve an individual parameter
     * @param  string $param The parameter to retrieve
     * @return mixed The parameter value
     */
    public function get($param)
    {
        if (in_array($param, array_keys($this->requestData))) {
            return $this->requestData[$param];
        } else {
            return false;
        }
    }
}
