<?php

namespace League\Container\Inflector;

use IteratorAggregate;
use League\Container\ContainerAwareInterface;

interface InflectorAggregateInterface extends ContainerAwareInterface, IteratorAggregate
{
    /**
     * Add an inflector to the aggregate.
     *
     * @param  string   $type
     * @param  callable $callback
     *
     * @return \League\Container\Inflector\Inflector
     */
    public function add( $type, callable $callback = null) ;

    /**
     * Applies all inflectors to an object.
     *
     * @param  object $object
     * @return object
     */
    public function inflect($object);
}
