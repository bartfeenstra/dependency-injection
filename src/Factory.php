<?php

/**
 * @file
 * Contains \BartFeenstra\DependencyInjection\Factory.
 */

namespace BartFeenstra\DependencyInjection;

/**
 * Defines a class factory.
 */
interface Factory {

    /**
     * Instantiates a class.
     *
     * @param string $className
     *   The fully qualified name of the class to instantiate.
     * @param mixed[] $dependencies
     *   The class constructor's dependencies and/or argument values, keyed by
     *   argument name.
     *
     * @return object
     *   An instance of $className.
     */
    public function instantiate($className, array $dependencies = []);

}
