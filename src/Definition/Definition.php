<?php

namespace League\Container\Definition;

use  League\Container\Argument\ArgumentResolverInterface;
use  League\Container\Argument\ArgumentResolverTrait;
use  League\Container\Argument\ClassNameInterface;
use  League\Container\Argument\RawArgumentInterface;
use League\Container\ContainerAwareTrait;
use ReflectionClass;

class Definition implements ArgumentResolverInterface, DefinitionInterface
{
    use ArgumentResolverTrait;
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var mixed
     */
    protected $concrete;

    /**
     * @var boolean
     */
    protected $shared = false;

    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @var mixed
     */
    protected $resolved;

    /**
     * Constructor.
     *
     * @param string $id
     * @param mixed  $concrete
     */
    public function __construct($id, $concrete = null)
    {
        $concrete = $concrete ?: $id;

        $this->alias    = $id;
        $this->concrete = $concrete;
    }

    /**
     * {@inheritdoc}
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTag($tag)
    {
        return in_array($tag, $this->tags);
    }

    /**
     * {@inheritdoc}
     */
    public function setAlias($id)
    {
        $this->alias = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * {@inheritdoc}
     */
    public function setShared($shared = true)
    {
        $this->shared = $shared;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isShared()
    {
        return $this->shared;
    }

    /**
     * {@inheritdoc}
     */
    public function getConcrete()
    {
        return $this->concrete;
    }

    /**
     * {@inheritdoc}
     */
    public function setConcrete($concrete)
    {
        $this->concrete = $concrete;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addArgument($arg)
    {
        $this->arguments[] = $arg;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addArguments(array $args)
    {
        foreach ($args as $arg) {
            $this->addArgument($arg);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMethodCall($method, array $args = [])
    {
        $this->methods[] = [
            'method'    => $method,
            'arguments' => $args,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMethodCalls(array $methods = [])
    {
        foreach ($methods as $method => $args) {
            $this->addMethodCall($method, $args);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($new = false)
    {
        $concrete = $this->concrete;

        if ($this->isShared() && ! is_null($this->resolved) && $new === false) {
            return $this->resolved;
        }

        if (is_callable($concrete)) {
            $concrete = $this->resolveCallable($concrete);
        }

        if ($concrete instanceof RawArgumentInterface) {
            $this->resolved = $concrete->getValue();

            return $concrete->getValue();
        }

        if ($concrete instanceof ClassNameInterface) {
            $concrete = $concrete->getValue();
        }

        if (is_string($concrete) && class_exists($concrete)) {
            $concrete = $this->resolveClass($concrete);
        }

        if (is_object($concrete)) {
            $concrete = $this->invokeMethods($concrete);
        }

        $this->resolved = $concrete;

        return $concrete;
    }

    /**
     * Resolve a callable.
     *
     * @param callable $concrete
     *
     * @return mixed
     */
    protected function resolveCallable(callable $concrete)
    {
        $resolved = $this->resolveArguments($this->arguments);

        return call_user_func_array($concrete, $resolved);
    }

    /**
     * Resolve a class.
     *
     * @param string $concrete
     *
     * @return object
     */
    protected function resolveClass($concrete)
    {
        $resolved   = $this->resolveArguments($this->arguments);
        $reflection = new \ReflectionClass($concrete);

        return $reflection->newInstanceArgs($resolved);
    }

    /**
     * Invoke methods on resolved instance.
     *
     * @param object $instance
     *
     * @return object
     */
    protected function invokeMethods($instance)
    {
        foreach ($this->methods as $method) {
            $args = $this->resolveArguments($method[ 'arguments' ]);
            call_user_func_array([$instance, $method[ 'method' ]], $args);
        }

        return $instance;
    }
}
