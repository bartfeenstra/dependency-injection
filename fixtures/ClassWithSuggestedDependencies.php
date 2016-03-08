<?php

namespace BartFeenstra\DependencyRetriever\Fixtures;

/**
 * Defines a class with suggested dependencies.
 */
class ClassWithSuggestedDependencies
{

    /**
     * Constructs a new instance.
     *
     * @suggestedDependency golden:non_existent $baz
     * @suggestedDependency labrador:bar $bar
     * @suggestedDependency golden:foo $foo
     *
     * @param \stdClass $qux
     *   No suggested dependency for this parameter exists. Its purpose is to
     *   receive an overridden dependency, or have an exception thrown because
     *   its dependency is missing.
     * @param mixed[] $baz
     *   No suggested dependency for this parameter can be retrieved. Its
     *   purpose is to receive an overriden dependency or none at all.
     * @param \BartFeenstra\DependencyRetriever\Fixtures\DependencyBar|null $bar
     * @param \BartFeenstra\DependencyRetriever\Fixtures\DependencyFoo $foo
     */
    public function __construct(\stdClass $qux, array $baz = [], DependencyBar $bar = null, DependencyFoo $foo)
    {
    }

}
