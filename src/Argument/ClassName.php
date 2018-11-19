<?php

namespace League\Container\Argument;

class ClassName implements ClassNameInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * Construct.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }
}
