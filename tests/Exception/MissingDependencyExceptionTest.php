<?php

namespace BartFeenstra\DependencyRetriever\Tests\Exception;

use BartFeenstra\DependencyRetriever\Exception\MissingDependencyException;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetriever\Exception\MissingDependencyException
 */
class MissingDependencyExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The names of the missing arguments.
     *
     * @var string[]
     */
    protected $argumentNames = ['bar', 'foo', 'qux'];

    /**
     * The name of the class for which dependencies are missing.
     *
     * @var string
     */
    protected $className = 'baz';

    /**
     * The subject under test.
     *
     * @var \BartFeenstra\DependencyRetriever\Exception\MissingDependencyException
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new MissingDependencyException($this->className, $this->argumentNames);
    }

    /**
     * @covers ::getClassName
     * @covers ::__construct
     */
    public function testGetClassName()
    {
        $this->assertSame($this->className, $this->sut->getClassName());
    }

    /**
     * @covers ::getArgumentNames
     * @covers ::__construct
     */
    public function testGetArgumentNames()
    {
        $this->assertSame($this->argumentNames, $this->sut->getArgumentNames());
    }
}
