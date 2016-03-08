<?php

namespace BartFeenstra\DependencyRetriever;

use BartFeenstra\DependencyRetriever\Exception\MissingDependencyException;

/**
 * Provides a simple class factory.
 */
class SimpleFactory implements Factory
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
     * Constructs a new instance.
     *
     * @param \BartFeenstra\DependencyRetriever\SuggestedDependencyFinder $suggestedDependencyFinder
     *   The suggested dependency finder.
     * @param \BartFeenstra\DependencyRetriever\DependencyRetriever $dependencyRetriever
     *   The dependency retriever.
     */
    public function __construct(
        SuggestedDependencyFinder $suggestedDependencyFinder,
        DependencyRetriever $dependencyRetriever
    ) {
        $this->dependencyRetriever = $dependencyRetriever;
        $this->suggestedDependencyFinder = $suggestedDependencyFinder;
    }

    public function instantiate($className, array $overrideDependencies = [])
    {
        $class = new \ReflectionClass($className);

        // Instantiate quickly if there is no constructor.
        if (!$class->hasMethod('__construct')) {
            return new $className();
        }

        $method = $class->getMethod('__construct');

        // Instantiate quickly if the constructor has no arguments.
        if ($method->getNumberOfParameters() === 0) {
            return new $className();
        }

        // Retrieve dependencies that aren't overridden.
        $suggestedDependencyIds = $this->suggestedDependencyFinder->findSuggestedDependencyIds($className);
        $retrievedDependencies = [];
        foreach (array_diff_key($suggestedDependencyIds, $overrideDependencies) as $argumentName => $argumentSuggestedDependencyIds) {
            $retrieverName = $this->dependencyRetriever->respondsTo();
            if (isset($argumentSuggestedDependencyIds[$retrieverName]) && $this->dependencyRetriever->seesDependency($argumentSuggestedDependencyIds[$retrieverName])) {
                $retrievedDependencies[$argumentName] =  $this->dependencyRetriever->retrieveDependency($argumentSuggestedDependencyIds[$retrieverName]);
            }
        };
        $dependencies = $overrideDependencies + $retrievedDependencies;

        // Build a list of constructor arguments, keyed by argument name.
        /** @var \ReflectionParameter[] $arguments */
        $arguments = array_combine(array_map(function(\ReflectionParameter $argument) {
            return $argument->getName();
        }, $method->getParameters()), $method->getParameters());

        // Add default values for missing dependencies.
        foreach ($arguments as $argument) {
            if (!array_key_exists($argument->getName(), $dependencies) && $argument->isDefaultValueAvailable()) {
                $dependencies[$argument->getName()] = $argument->getDefaultValue();
            }
        }

        // Check if we have values for all arguments.
        $namesOfArgumentsWithoutValues = array_diff_key($arguments, $dependencies);
        if ($namesOfArgumentsWithoutValues) {
            throw new MissingDependencyException(reset($namesOfArgumentsWithoutValues));
        }

        // We now have dependencies for all arguments. Put them in the correct
        // order.
        uksort($dependencies, function ($argumentAName, $argumentBName) use ($arguments) {
            $argumentAPosition = $arguments[$argumentAName]->getPosition();
            $argumentBPosition = $arguments[$argumentBName]->getPosition();
            if ($argumentAPosition == $argumentBPosition) {
                return 0;
            }
            elseif ($argumentAPosition > $argumentBPosition) {
                return 1;
            }
            else {
                return -1;
            }
        });

        return new $className(...array_values($dependencies));
    }

}
