<?php

namespace BartFeenstra\DependencyRetriever\Factory;

/**
 * Instantiates classes.
 */
interface Factory
{

    /**
     * Instantiates a class.
     *
     * @param string  $className
     *   The fully qualified name of the class to instantiate.
     * @param mixed[] $overrideDependencies
     *   Keys are constructor argument names, and values are argument values.
     *   These override suggested dependencies and arguments' default values.
     *
     * @return object
     *   An instance of $className.
     */
    public function instantiate($className, array $overrideDependencies = []);
}
