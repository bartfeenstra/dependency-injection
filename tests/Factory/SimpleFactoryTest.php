<?php

namespace BartFeenstra\DependencyRetriever\Tests\Factory;

use BartFeenstra\DependencyRetriever\DependencySuggestion\Suggestion;
use BartFeenstra\DependencyRetriever\Fixtures\DependencyBar;
use BartFeenstra\DependencyRetriever\Retriever\Retriever;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithInheritedConstructorWithSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithoutConstructor;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithoutSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\ClassWithSuggestedDependencies;
use BartFeenstra\DependencyRetriever\Fixtures\DependencyFoo;
use BartFeenstra\DependencyRetriever\Factory\SimpleFactory;
use BartFeenstra\DependencyRetriever\DependencySuggestion\Finder;

/**
 * @coversDefaultClass \BartFeenstra\DependencyRetriever\Factory\SimpleFactory
 */
class SimpleFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The suggested dependency finder.
     *
     * @var \BartFeenstra\DependencyRetriever\DependencySuggestion\Finder
     */
    protected $suggestedDependencyFinder;

    /**
     * The "golden" dependency retriever.
     *
     * @var \BartFeenstra\DependencyRetriever\Retriever\Retriever
     */
    protected $goldenDependencyRetriever;

    /**
     * The "labrador" dependency retriever.
     *
     * @var \BartFeenstra\DependencyRetriever\Retriever\Retriever
     */
    protected $labradorDependencyRetriever;

    /**
     * The subject under test.
     *
     * @var \BartFeenstra\DependencyRetriever\Factory\Factory
     */
    protected $sut;

    public function setUp()
    {
        $this->suggestedDependencyFinder = $this->prophesize(Finder::class);

        $foo = $this->prophesize(DependencyFoo::class);
        $bar = $this->prophesize(DependencyBar::class);

        $this->goldenDependencyRetriever = $this->prophesize(Retriever::class);
        $this->goldenDependencyRetriever->getName()->willReturn('golden');
        $this->goldenDependencyRetriever->knowsDependency('foo')->willReturn(true);
        $this->goldenDependencyRetriever->knowsDependency('non_existent')->willReturn(false);
        $this->goldenDependencyRetriever->retrieveDependency('foo')->willReturn($foo->reveal());

        $this->labradorDependencyRetriever = $this->prophesize(Retriever::class);
        $this->labradorDependencyRetriever->getName()->willReturn('labrador');
        $this->labradorDependencyRetriever->knowsDependency('bar')->willReturn(true);
        $this->labradorDependencyRetriever->knowsDependency('non_existent')->willReturn(false);
        $this->labradorDependencyRetriever->retrieveDependency('bar')->willReturn($bar->reveal());

        $this->sut = new SimpleFactory(
            $this->suggestedDependencyFinder->reveal(),
            [$this->goldenDependencyRetriever->reveal(), $this->labradorDependencyRetriever->reveal()]
        );
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
                new Suggestion('labrador', 'non_existent'),
            ],
            'bar' => [
                new Suggestion('labrador', 'bar'),
            ],
            'foo' => [
                new Suggestion('golden', 'foo'),
            ],
        ];
        return [
            [ClassWithSuggestedDependencies::class, $suggestedDependencies],
            [ClassWithInheritedConstructorWithSuggestedDependencies::class, $suggestedDependencies]
        ];
    }

    /**
     * @covers ::addDependencyRetriever
     *
     * @dataProvider provideInstantiateWithSuggestedDependencies
     *
     * @param string $className
     *   The fully qualified name of the class to instantiate.
     * @param array[] $suggestedDependencyIds
     *   Keys are constructor argument names, and values are arrays of which
     *   keys are dependency retriever names, and values are dependency IDs.
     */
    public function testInstantiateWithSuggestedConstructorDependenciesAndSetterRetrieverInjection(
        $className,
        array $suggestedDependencyIds = []
    ) {
        $this->sut = new SimpleFactory(
            $this->suggestedDependencyFinder->reveal()
        );
        $this->sut->addDependencyRetriever($this->goldenDependencyRetriever->reveal());
        $this->sut->addDependencyRetriever($this->labradorDependencyRetriever->reveal());

        $this->suggestedDependencyFinder->findSuggestedDependencyIds($className)->willReturn($suggestedDependencyIds);

        $overriddenDependencies = [
            'qux' => new \stdClass(),
        ];

        $this->assertInstanceOf($className, $this->sut->instantiate($className, $overriddenDependencies));
    }
}
