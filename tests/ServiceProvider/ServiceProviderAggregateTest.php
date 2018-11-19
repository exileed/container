<?php

namespace League\Container\Test\ServiceProvider;

use League\Container\Container;
use League\Container\ContainerAwareTrait;
use League\Container\Exception\ContainerException;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use League\Container\ServiceProvider\ServiceProviderAggregate;
use League\Container\ServiceProvider\ServiceProviderInterface;

class ServiceProviderAggregateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Return a service provider fake
     *
     * @return \League\Container\ServiceProvider\ServiceProviderInterface
     */
    protected function getServiceProvider()
    {
        return new class extends AbstractServiceProvider implements BootableServiceProviderInterface {
            use ContainerAwareTrait;

            protected $provides = [
                'SomeService',
                'AnotherService'
            ];

            public $registered = 0;

            public function boot()
            {
                return true;
            }

            public function register()
            {
                $this->registered++;

                $this->getContainer()->add('SomeService', function ($arg) {
                    return $arg;
                });

                return true;
            }
        };
    }

    /**
     * Asserts that the aggregate adds a class name service provider.
     */
    public function testAggregateAddsClassNameServiceProvider()
    {
        $container = $this->getMockBuilder(Container::class)->getMock();
        $aggregate = (new ServiceProviderAggregate)->setContainer($container);

        $aggregate->add($this->getServiceProvider());

        $this->assertTrue($aggregate->provides('SomeService'));
        $this->assertTrue($aggregate->provides('AnotherService'));
    }

    /**
     * Asserts that an exception is thrown when adding a service provider that
     * does not exist.
     */
    public function testAggregateThrowsWhenCannotResolveServiceProvider()
    {
        $this->expectException(ContainerException::class);

        $container = $this->getMockBuilder(Container::class)->getMock();
        $aggregate = (new ServiceProviderAggregate)->setContainer($container);

        $aggregate->add('NonExistentClass');
    }

    /**
     * Asserts that an exception is thrown when attempting to invoke the register
     * method of a service provider that has not been provided.
     */
    public function testAggregateThrowsWhenRegisteringForServiceThatIsNotAdded()
    {
        $this->expectException(ContainerException::class);

        $container = $this->getMockBuilder(Container::class)->getMock();
        $aggregate = (new ServiceProviderAggregate)->setContainer($container);

        $aggregate->register('SomeService');
    }

    /**
     * Asserts that resgister method is only invoked once per service provider.
     */
    public function testAggregateInvokesCorrectRegisterMethodOnlyOnce()
    {
        $container = $this->getMockBuilder(Container::class)->getMock();
        $aggregate = (new ServiceProviderAggregate)->setContainer($container);
        $provider  = $this->getServiceProvider();

        $aggregate->add($provider);

        $aggregate->register('SomeService');
        $aggregate->register('AnotherService');

        $this->assertSame(1, $provider->registered);
    }
}
