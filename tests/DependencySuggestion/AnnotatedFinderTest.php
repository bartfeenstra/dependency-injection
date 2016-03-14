<?php

namespace BartFeenstra\DependencyRetriever\Tests\DependencySuggestion;

use BartFeenstra\DependencyRetriever\DependencySuggestion\AnnotatedFinder;
use BartFeenstra\DependencyRetriever\DependencySuggestion\Suggestion;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithInheritedConstructorWithSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithoutConstructor;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithoutSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithSuggestedDependencies;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetriever\DependencySuggestion\AnnotatedFinder
 */
class AnnotatedFinderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The subject under test.
     *
     * @var \BartFeenstra\DependencyRetriever\DependencySuggestion\AnnotatedFinder
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new AnnotatedFinder();
    }

    /**
     * @covers ::findSuggestedDependencyIds
     *
     * @dataProvider provideFindSuggestedDependencies
     *
     * @param array[] $expectedSuggestedDependencies
     *   Keys are constructor argument names, and values are arrays of which
     *   keys are dependency retriever names, and values are dependency IDs.
     * @param string $className
     *   The fully qualified name of the class to instantiate.
     */
    public function testFindSuggestedDependencies(array $expectedSuggestedDependencies, $className)
    {
        $this->assertEquals($expectedSuggestedDependencies, $this->sut->findSuggestedDependencyIds($className));
    }

    /**
     * Provides data to self::testFindSuggestedDependencies().
     *
     * @return array[]
     *   Array items are arrays with the following items:
     *   - An array of which keys are constructor argument names, and values
     *     are arrays of which keys are dependency retriever names, and values
     *     are dependency IDs.
     *   - The fully qualified name of the class to find suggested dependencies
     *     for.
     */
    public function provideFindSuggestedDependencies()
    {
        $suggestedDependencies = [
            'baz' => [
                new Suggestion('golden', 'non_existent'),
            ],
            'bar' => [
                new Suggestion('labrador', 'bar'),
            ],
            'foo' => [
                new Suggestion('golden', 'foo'),
            ],
        ];
        return [
            [$suggestedDependencies, ClassWithSuggestedDependencies::class],
            [$suggestedDependencies, ClassWithInheritedConstructorWithSuggestedDependencies::class],
            [[], ClassWithoutSuggestedDependencies::class],
            [[], ClassWithoutConstructor::class],
        ];
    }
}
