<?php

namespace League\Container\ServiceProvider;

use League\Container\ContainerAwareTrait;

abstract class AbstractServiceProvider implements ServiceProviderInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    protected $provides = [];

    /**
     * @var string
     */
    protected $identifier;

    /**
     * {@inheritdoc}
     */
    public function provides($alias)
    {
        return (in_array($alias, $this->provides));
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($id)
    {
        $this->identifier = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->identifier ?: get_class($this);
    }
}
