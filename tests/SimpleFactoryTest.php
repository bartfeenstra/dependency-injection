<?php

namespace BartFeenstra\DependencyRetriever\Tests;

use BartFeenstra\DependencyRetriever\DependencyRetriever;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithInheritedConstructorWithSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithoutConstructor;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithoutSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\DependencyBar;
use BartFeenstra\DependencyRetriever\Fixtures\DependencyFoo;
use BartFeenstra\DependencyRetriever\SimpleFactory;
use BartFeenstra\DependencyRetriever\SuggestedDependencyFinder;

;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetriever\SimpleFactory
 */
class SimpleFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The suggested dependency finder.
     *
     * @var \BartFeenstra\DependencyRetriever\SuggestedDependencyFinder
     */
    protected $suggestedDependencyFinder;

    /**
     * The dependency retriever.
     *
     * @var \BartFeenstra\DependencyRetriever\DependencyRetriever
     */
    protected $dependencyRetriever;

    /**
     * The subject under test.
     *
     * @var \BartFeenstra\DependencyRetriever\Factory
     */
    protected $sut;

    public function setUp()
    {
        $this->suggestedDependencyFinder = $this->prophesize(SuggestedDependencyFinder::class);

        $foo = $this->prophesize(DependencyFoo::class);

        $this->dependencyRetriever = $this->prophesize(DependencyRetriever::class);
        $this->dependencyRetriever->respondsTo()->willReturn('golden');
        $this->dependencyRetriever->seesDependency('foo')->willReturn(true);
        $this->dependencyRetriever->seesDependency('non_existent')->willReturn(false);
        $this->dependencyRetriever->retrieveDependency('foo')->willReturn($foo->reveal());

        $this->sut = new SimpleFactory($this->suggestedDependencyFinder->reveal(), $this->dependencyRetriever->reveal());
    }

    /**
     * @covers ::instantiate
     * @covers ::__construct
     */
    public function testInstantiateWithoutConstructor()
    {
        $className = ClassWithoutConstructor::class;
        $this->assertInstanceOf($className, $this->sut->instantiate($className));
    }

    /**
     * @covers ::instantiate
     * @covers ::__construct
     */
    public function testInstantiateWithoutConstructorDependencies()
    {
        $className = ClassWithoutSuggestedDependencies::class;
        $this->assertInstanceOf($className, $this->sut->instantiate($className));
    }

    /**
     * @covers ::instantiate
     * @covers ::__construct
     *
     * @expectedException \BartFeenstra\DependencyRetriever\Exception\MissingDependencyException
     */
    public function testInstantiateWithMissingOverriddenConstructorDependencies()
    {
        $className = ClassWithSuggestedDependencies::class;

        $this->suggestedDependencyFinder->findSuggestedDependencyIds($className)->willReturn([]);

        $this->assertInstanceOf($className, $this->sut->instantiate($className));
    }

    /**
     * @covers ::instantiate
     * @covers ::__construct
     *
     * @dataProvider provideInstantiateWithSuggestedDependencies
     *
     * @param string $className
     *   The fully qualified name of the class to instantiate.
     * @param array[] $suggestedDependencyIds
     *   Keys are constructor argument names, and values are arrays of which
     *   keys are dependency retriever names, and values are dependency IDs.
     */
    public function testInstantiateWithSuggestedConstructorDependencies($className, array $suggestedDependencyIds = [])
    {
        $this->suggestedDependencyFinder->findSuggestedDependencyIds($className)->willReturn($suggestedDependencyIds);

        $overriddenDependencies = [
            'qux' => new \stdClass(),
        ];

        $this->assertInstanceOf($className, $this->sut->instantiate($className, $overriddenDependencies));
    }

    /**
     * Provides data to self::testInstantiateWithSuggestedDependencies().
     *
     * @return array[]
     *   Array items are arrays with the following items:
     *   - The fully qualified name of the class to find suggested dependencies
     *     for.
     *   - Keys are constructor argument names, and values are arrays of which
     *     keys are dependency retriever names, and values are dependency IDs.
     */
    public function provideInstantiateWithSuggestedDependencies()
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
            [ClassWithSuggestedDependencies::class, $suggestedDependencies],
            [ClassWithInheritedConstructorWithSuggestedDependencies::class, $suggestedDependencies]
        ];
    }

}
