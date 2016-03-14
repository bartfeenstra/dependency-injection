<?php

namespace BartFeenstra\DependencyRetriever\Tests\DependencySuggestion;

use BartFeenstra\DependencyRetriever\DependencySuggestion\Suggestion;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetriever\DependencySuggestion\Suggestion
 */
class SuggestionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The name of the dependency retriever.
     *
     * @var string
     */
    protected $dependencyRetrieverName = 'baz';

    /**
     * The ID of the dependency with its retriever.
     *
     * @var string
     */
    protected $dependencyId = 'bar';

    /**
     * The subject under test.
     *
     * @var \BartFeenstra\DependencyRetriever\DependencySuggestion\Suggestion
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new Suggestion($this->dependencyRetrieverName, $this->dependencyId);
    }

    /**
     * @covers ::getDependencyRetrieverName
     * @covers ::__construct
     */
    public function testGetDependencyRetrieverName()
    {
        $this->assertSame($this->dependencyRetrieverName, $this->sut->getDependencyRetrieverName());
    }

    /**
     * @covers ::getDependencyId
     * @covers ::__construct
     */
    public function testGetDependencyId()
    {
        $this->assertSame($this->dependencyId, $this->sut->getDependencyId());
    }

}
