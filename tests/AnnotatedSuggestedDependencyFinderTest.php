<?php

/**
 * @file
 * Contains \BartFeenstra\DependencyRetriever\Tests\AnnotatedSuggestedDependencyFinderTest.
 */

namespace BartFeenstra\DependencyRetriever\Tests;

use BartFeenstra\DependencyRetriever\AnnotatedSuggestedDependencyFinder;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithInheritedConstructorWithSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithoutConstructor;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithoutSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithSuggestedDependencies;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetriever\AnnotatedSuggestedDependencyFinder
 */
class AnnotatedSuggestedDependencyFinderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The subject under test.
     *
     * @var \BartFeenstra\DependencyRetriever\AnnotatedSuggestedDependencyFinder
     */
    protected $sut;

    public function setUp()
    {
        $this->sut = new AnnotatedSuggestedDependencyFinder();
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
        $this->assertSame($expectedSuggestedDependencies, $this->sut->findSuggestedDependencyIds($className));
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
                'golden' => 'non_existent',
            ],
            'bar' => [
                'labrador' => 'bar',
            ],
            'foo' => [
                'golden' => 'foo',
            ],
        ];
        return [
            [$suggestedDependencies, ClassWithSuggestedDependencies::class],
            [$suggestedDependencies, ClassWithInheritedConstructorWithSuggestedDependencies::class],
            [[], ClassWithoutSuggestedDependencies::class],
            [[], ClassWithoutConstructor::class],
        ];
    }

    /**
     * @covers ::findSuggestedDependencyIds
     *
     * @expectedException \BartFeenstra\DependencyRetriever\Exception\ClassNotFoundException
     */
    public function testFindSuggestedDependenciesWithInvalidClassName()
    {
        $this->sut->findSuggestedDependencyIds('\BartFeenstra\DependencyRetriever\NonExistentClass');
    }

}
