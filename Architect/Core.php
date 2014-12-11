<?php
namespace Architect;

use \Slim\Slim;
use \Pimple\Container;

/**
 * Architect\Core
 *
 * Defines and runs the core rendering Architect
 *
 * @category Architect
 * @package Core
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class Core extends ArchitectAbstract
{
    public static $app;

    /**
     * Constructor
     */
    public function __construct(Container $container)
    {
        // Optimistically add the ContentTypes middleware
        // This doesn't seem to work yet
        $container['slim']->add(new \Slim\Middleware\ContentTypes());

        // Set the container to be available to the core
        $this->container = $container;
    }

    /**
     * Define the routes and return output for each REST method
     * @throws \RuntimeException
     */
    public function routes()
    {
        // Handle any exceptions
        $this->container['slim']->error(function (\Exception $exception) {
            var_dump($exception);

            $data = array(
                'Message' => $exception->getMessage(),
                'Code' => $exception->getCode(),
            );
            $code = $exception->getCode();

            $this->displayResponse($data, $code);
        });

        // GET requests
        $this->container['slim']->get('/:class/:identifier', function ($class, $identifier) {
            $this->respond($class, 'read', $identifier);
        });

        $this->container['slim']->get('/:class', function ($class) {
            $this->respond($class, 'read');
        });

        // PUT requests
        $this->container['slim']->put('/:class/:identifier', function ($class, $identifier) {
            $this->respond($class, 'update', $identifier);
        });

        $this->container['slim']->put('/:class', function ($class) {
            throw new \RuntimeException(
                'A resource identifier must be specified when using PUT',
                ResponseCode::ERROR_NOMETHOD
            );
        });

        // POST requests
        $this->container['slim']->post('/:class/:identifier', function ($class, $identifier) {
            throw new \RuntimeException(
                'A resource identifier cannot be specified when using POST',
                ResponseCode::ERROR_NOMETHOD
            );
        });

        $this->container['slim']->post('/:class', function ($class) {
            $this->respond($class, 'create');
        });

        // DELETE requests
        $this->container['slim']->delete('/:class/:identifier', function ($class, $identifier) {
            $this->respond($class, 'delete', $identifier);
        });

        $this->container['slim']->delete('/:class', function ($class) {
            throw new \RuntimeException(
                'A resource identifier must be specified when using DELETE',
                ResponseCode::ERROR_NOMETHOD
            );
        });

        // OPTIONS requests
        $this->container['slim']->options('/(:name+)', function () {
            $this->container['request']->validate();
            $this->container['slim']->response()->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
            $this->container['slim']->response()->header('Access-Control-Allow-Headers', 'Content-Type');
        });
    }

    /**
     * Respond to a successful route
     * @param string $class The class name
     * @param string $method The method to be called
     * @param int $identifier The identifier of the resource
     * @throws \LogicException
     */
    private function respond($class, $method, $identifier = null)
    {
        // Validate the request
        $this->container['request']->validate();

        // Make it a controller namespace
        $fullclass = '\\Architect\\Controllers\\' . ucfirst($class);

        // 404 if the class doesn't exist
        if (!class_exists($fullclass)) {
            $this->container['slim']->halt(404);
        }

        // Otherwise, instantiate it and pass it the container
        $loadedClass = new $fullclass($this->container);

        // Throw an exception when the method hasn't been created yet
        if (!method_exists($loadedClass, $method)) {
            throw new \LogicException('Method does not exist in resource', ResponseCode::ERROR_NOTIMPLEMENTED);
        }

        // Call the method with or without the identifier accordingly
        if (!is_null($identifier)) {
            $result = $loadedClass->$method($identifier);
        } else {
            $result = $loadedClass->$method();
        }

        // Check the result is an actual result
        if (!($result instanceof Result)) {
            throw new \LogicException('No result sent from API', ResponseCode::ERROR_INTERNAL);
        }

        // Get the data and code
        $data = $result->getData();
        $code = $result->getCode();

        // Display it!
        $this->displayResponse($data, $code);
    }

    /**
     * Output a response
     * @param array $data The data
     * @param int $code The response code
     */
    private function displayResponse($data, $code)
    {
        // Default to 200 if all else fails
        if (empty($code)) {
            $code = ResponseCode::OK;
        }

        // Get the response from Slim
        $response = $this->container['slim']->response();

        // Set the headers
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header("Content-Type", "application/json");

        // If our status code is an internal one, just give a generic 500
        if ($code > 599) {
            $response->setStatus(ResponseCode::ERROR_INTERNAL);
        } else {
            $response->setStatus($code);
        }

        // Output the response!
        $response->write(json_encode($data));
    }
}
