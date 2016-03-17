<?php

namespace BartFeenstra\DependencyRetriever\Factory;

use BartFeenstra\DependencyRetriever\Retriever\Retriever;
use BartFeenstra\DependencyRetriever\DependencySuggestion\Finder;
use BartFeenstra\DependencyRetriever\Exception\MissingDependencyException;

/**
 * Provides a simple class factory.
 */
class SimpleFactory implements Factory
{

    /**
     * The suggested dependency finder.
     *
     * @var \BartFeenstra\DependencyRetriever\DependencySuggestion\Finder
     */
    protected $suggestedDependencyFinder;

    /**
     * The dependency retrievers.
     *
     * @var \BartFeenstra\DependencyRetriever\Retriever\Retriever[]
     */
    protected $dependencyRetrievers = [];

    /**
     * Constructs a new instance.
     *
     * @param \BartFeenstra\DependencyRetriever\DependencySuggestion\Finder $suggestedDependencyFinder
     *   The suggested dependency finder.
     * @param \BartFeenstra\DependencyRetriever\Retriever\Retriever[] $dependencyRetrievers
     *   The dependency retrievers in order of decreasing priority.
     */
    public function __construct(
        Finder $suggestedDependencyFinder,
        array $dependencyRetrievers = []
    ) {
        foreach ($dependencyRetrievers as $dependencyRetriever) {
            $this->dependencyRetrievers[$dependencyRetriever->getName()] = $dependencyRetriever;
        }
        $this->suggestedDependencyFinder = $suggestedDependencyFinder;
    }

    /**
     * Adds another dependency retriever.
     *
     * The newly added retriever will receive a lower priority than any
     * existing retrievers. If a retriever with the same name already exists,
     * it is kept.
     *
     * @param \BartFeenstra\DependencyRetriever\Retriever\Retriever $dependencyRetriever
     */
    public function addDependencyRetriever(Retriever $dependencyRetriever)
    {
        if (!array_key_exists($dependencyRetriever->getName(), $this->dependencyRetrievers)) {
            $this->dependencyRetrievers[$dependencyRetriever->getName()] = $dependencyRetriever;
        }
    }

    public function instantiate($className, array $overrideDependencies = [])
    {
        $class = new \ReflectionClass($className);

        // Instantiate quickly if there is no constructor.
        if (!$class->hasMethod('__construct')) {
            return new $className();
        }

        $method = $class->getMethod('__construct');

        // Get the argument names and set default values.
        $arguments = [];
        $defaultDependencies = [];
        foreach ($method->getParameters() as $argument) {
            $arguments[$argument->getName()] = $argument;
            if ($argument->isDefaultValueAvailable()) {
                $defaultDependencies[$argument->getName()] = $argument->getDefaultValue();
            }
        }

        // Instantiate quickly if the constructor has no arguments.
        if (!$arguments) {
            return new $className();
        }

        // Retrieve dependencies that aren't overridden.
        $suggestedDependencyIds = $this->suggestedDependencyFinder->findSuggestedDependencyIds($className);
        $suggestedDependencies = [];
        foreach (array_diff_key(
            $suggestedDependencyIds,
            $overrideDependencies
        ) as $argumentName => $argumentSuggestedDependencyIds) {
            foreach ($this->dependencyRetrievers as $dependencyRetriever) {
                $retrieverName = $dependencyRetriever->getName();
                if (isset($argumentSuggestedDependencyIds[$retrieverName]) &&
                    $dependencyRetriever->knowsDependency($argumentSuggestedDependencyIds[$retrieverName])
                ) {
                    $suggestedDependencies[$argumentName] =
                        $dependencyRetriever->retrieveDependency($argumentSuggestedDependencyIds[$retrieverName]);
                }
            }
        };

        // Merge dependencies.
        $dependencies = array_merge($defaultDependencies, $suggestedDependencies, $overrideDependencies);

        // Check if we have values for all arguments.
        $namesOfArgumentsWithoutValues = array_diff_key($arguments, $dependencies);
        if ($namesOfArgumentsWithoutValues) {
            throw new MissingDependencyException($className, $namesOfArgumentsWithoutValues);
        }

        // We now have dependencies for all arguments. Put them in the correct
        // order.
        uksort(
            $dependencies,
            function ($argumentAName, $argumentBName) use ($arguments) {
                /**
                 * @var \ReflectionParameter[] $arguments
                 */
                $argumentAPosition = $arguments[$argumentAName]->getPosition();
                $argumentBPosition = $arguments[$argumentBName]->getPosition();
                if ($argumentAPosition == $argumentBPosition) {
                    return 0;
                } elseif ($argumentAPosition > $argumentBPosition) {
                    return 1;
                } else {
                    return -1;
                }
            }
        );

        return new $className(...array_values($dependencies));
    }
}
