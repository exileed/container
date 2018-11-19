<?php

namespace League\Container\Test;

use League\Container\Argument\ClassName;

class ClassNameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Asserts that a raw argument object can set and get a value.
     */
    public function testClassNameSetsAndGetsArgument()
    {
        $arguments = [
            'string',
            'string2'
        ];

        foreach ($arguments as $expected) {
            $argument = new ClassName($expected);
            $this->assertSame($expected, $argument->getValue());
        }
    }
}
