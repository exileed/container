<?php

namespace League\Container\Inflector;

use League\Container\ContainerAwareTrait;
use League\Container\Argument\ArgumentResolverInterface;
use League\Container\Argument\ArgumentResolverTrait;

class Inflector implements ArgumentResolverInterface, InflectorInterface
{
    use ArgumentResolverTrait;
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * Construct.
     *
     * @param string        $type
     * @param callable|null $callback
     */
    public function __construct($type, callable $callback = null)
    {
        $this->type     = $type;
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function invokeMethod( $name, array $args)
    {
        $this->methods[$name] = $args;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function invokeMethods(array $methods)
    {
        foreach ($methods as $name => $args) {
            $this->invokeMethod($name, $args);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperty( $property, $value)
    {
        $this->properties[$property] = $this->resolveArguments([$value])[0];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $property => $value) {
            $this->setProperty($property, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function inflect($object)
    {
        $properties = $this->resolveArguments(array_values($this->properties));
        $properties = array_combine(array_keys($this->properties), $properties);

        foreach ($properties as $property => $value) {
            $object->{$property} = $value;
        }

        foreach ($this->methods as $method => $args) {
            $args = $this->resolveArguments($args);

            call_user_func_array([$object, $method], $args);
        }

        if (! is_null($this->callback)) {
            call_user_func_array($this->callback, [$object]);
        }
    }
}
