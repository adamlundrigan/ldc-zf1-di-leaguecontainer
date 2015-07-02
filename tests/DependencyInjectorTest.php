<?php
namespace LdcZf1DiLeagueContainerTest;

use LdcZf1DiLeagueContainer\DependencyInjector;

class DependencyInjectorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->bootstrap = \Mockery::mock('Zend_Application_Bootstrap');

        $this->frontController = \Zend_Controller_Front::getInstance();
        $this->frontController->setParam('bootstrap', $this->bootstrap);

        $this->controller = \Mockery::mock('Zend_Controller_Action');


        $this->container = \Mockery::mock('League\Container\Container');

        $this->object = new DependencyInjector();
        $this->object->setActionController($this->controller);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    public function testInstantiation()
    {
        $this->assertInstanceOf(
            'LdcZf1DiLeagueContainer\DependencyInjector',
            $this->object
        );
    }

    public function testHelperBailsEarlyIfControllerDoesNotDefineDependencies()
    {
        $this->bootstrap->shouldReceive('getResource')->never();
        $this->object->preDispatch();
    }

    public function testHelperBailsEarlyIfControllerDefinesDependenciesKeyAsNonArray()
    {
        $this->controller->dependencies = null;

        $this->bootstrap->shouldReceive('getResource')->never();
        $this->object->preDispatch();
    }

    public function testHelperBailsEarlyIfControllerDefinesDependenciesKeyAsEmptyArray()
    {
        $this->controller->dependencies = [];

        $this->bootstrap->shouldReceive('getResource')->never();
        $this->object->preDispatch();
    }

    public function testHelperBailsEarlyIfNoContainerIsDefined()
    {
        $this->controller->dependencies = ['foo'];

        $this->bootstrap->shouldReceive('getResource')
                        ->with('container')
                        ->once()
                        ->andReturn(null);

        $this->container->shouldReceive('isRegistered')->never();

        $this->object->preDispatch();
    }

    public function testHelperThrowsExceptionIfDependencyNotFound()
    {
        $this->controller->dependencies = ['foo'];

        $this->bootstrap->shouldReceive('getResource')
                        ->with('container')
                        ->once()
                        ->andReturn($this->container);

        $this->container->shouldReceive('isRegistered')
                ->with('foo')
                ->once()
                ->andReturn(false);

        $this->controller->shouldReceive('__set')
                ->with('foo', \Mockery::any())
                ->never();

        $this->setExpectedException('DomainException');
        $this->object->preDispatch();
    }

    public function testHelperInjectsNamedDependencies()
    {
        $this->controller->dependencies = [
            'foo',
            'bar' => 'serviceName',
        ];

        $this->bootstrap->shouldReceive('getResource')
                        ->with('container')
                        ->once()
                        ->andReturn($this->container);

        $mockDependency1 = new \stdClass();
        $this->container->shouldReceive('isRegistered')
                ->with('foo')
                ->once()
                ->andReturn(true);
        $this->container->shouldReceive('get')
                ->with('foo')
                ->once()
                ->andReturn($mockDependency1);

        $mockDependency2 = new \stdClass();
        $this->container->shouldReceive('isRegistered')
                ->with('serviceName')
                ->once()
                ->andReturn(true);
        $this->container->shouldReceive('get')
                ->with('serviceName')
                ->once()
                ->andReturn($mockDependency2);

        $this->object->preDispatch();

        $this->assertTrue(isset($this->controller->foo));
        $this->assertSame($mockDependency1, $this->controller->foo);
        $this->assertSame($mockDependency2, $this->controller->bar);
    }
}
