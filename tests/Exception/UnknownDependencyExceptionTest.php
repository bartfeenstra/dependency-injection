<?php

namespace BartFeenstra\DependencyRetriever\Tests\Exception;

use BartFeenstra\DependencyRetriever\Exception\UnknownDependencyException;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetriever\Exception\UnknownDependencyException
 */
class UnknownDependencyExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The name of the dependency retriever.
     *
     * @var string
     */
    protected $dependencyRetrieverName = 'Labrador';

    /**
     * The ID of the unknown dependency.
     *
     * @var string
     */
    protected $dependencyId = 'Ball';

    /**
     * The subject under test.
     *
     * @var \BartFeenstra\DependencyRetriever\Exception\UnknownDependencyException
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new UnknownDependencyException($this->dependencyRetrieverName, $this->dependencyId);
    }

    /**
     * @covers ::getDependencyRetrieverName
     * @covers ::__construct
     */
    public function testGetClassName()
    {
        $this->assertSame($this->dependencyRetrieverName, $this->sut->getDependencyRetrieverName());
    }

    /**
     * @covers ::getDependencyId
     * @covers ::__construct
     */
    public function testGetArgumentNames()
    {
        $this->assertSame($this->dependencyId, $this->sut->getDependencyId());
    }
}
