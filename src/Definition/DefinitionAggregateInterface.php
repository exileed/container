<?php

namespace League\Container\Definition;

use IteratorAggregate;
use League\Container\ContainerAwareInterface;

interface DefinitionAggregateInterface extends ContainerAwareInterface, IteratorAggregate
{
    /**
     * Add a definition to the aggregate.
     *
     * @param string  $id
     * @param mixed   $definition
     * @param boolean $shared
     *
     * @return \League\Container\Definition\DefinitionInterface
     */
    public function add( $id, $definition,  $shared = false) ;

    /**
     * Checks whether alias exists as definition.
     *
     * @param string $id
     *
     * @return boolean
     */
    public function has( $id);

    /**
     * Checks whether tag exists as definition.
     *
     * @param string $tag
     *
     * @return boolean
     */
    public function hasTag( $tag);

    /**
     * Get the definition to be extended.
     *
     * @param string $id
     *
     * @return \League\Container\Definition\DefinitionInterface
     */
    public function getDefinition($id);

    /**
     * Resolve and build a concrete value from an id/alias.
     *
     * @param string  $id
     * @param boolean $new
     *
     * @return mixed
     */
    public function resolve($id, $new = false);

    /**
     * Resolve and build an array of concrete values from a tag.
     *
     * @param string  $tag
     * @param boolean $new
     *
     * @return mixed
     */
    public function resolveTagged($tag, $new = false);
}
