<?php

namespace League\Container\Inflector;

interface InflectorInterface
{
    /**
     * Get the type.
     *
     * @return string
     */
    public function getType();

    /**
     * Defines a method to be invoked on the subject object.
     *
     * @param string $name
     * @param array  $args
     *
     * @return self
     */
    public function invokeMethod( $name, array $args) ;

    /**
     * Defines multiple methods to be invoked on the subject object.
     *
     * @param array $methods
     *
     * @return self
     */
    public function invokeMethods(array $methods) ;

    /**
     * Defines a property to be set on the subject object.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return self
     */
    public function setProperty( $property, $value) ;

    /**
     * Defines multiple properties to be set on the subject object.
     *
     * @param array $properties
     *
     * @return self
     */
    public function setProperties(array $properties) ;

    /**
     * Apply inflections to an object.
     *
     * @param object $object
     *
     * @return void
     */
    public function inflect($object);
}
