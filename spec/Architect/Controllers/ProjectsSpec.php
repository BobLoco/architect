<?php
namespace spec\Architect\Controllers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * spec\Architect\Controllers\ProjectsSpec
 *
 * Spec tests for Projects controller
 *
 * @category spec
 * @package Architect
 * @subpackage Controllers
 * @author Rob Lowcock <rob.lowcock@gmail.com>
 */
class ProjectsSpec extends ObjectBehavior
{
    /**
     * IoC Container set-up
     * @param Pimple\Container $container
     */
    public function let($container)
    {
        $container->beADoubleOf('Pimple\Container');
        $this->beConstructedWith($container);
    }

    /**
     * Check whether project class is initializable
     */
    public function it_is_initializable()
    {
        $this->shouldHaveType('Architect\Controllers\Projects');
    }
}
