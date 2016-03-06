<?php

/**
 * @file
 * Contains \BartFeenstra\DependencyInjection\DependencyCollector.
 */

namespace BartFeenstra\DependencyInjection;

/**
 * Defines a dependency collector.
 */
interface DependencyCollector {

    /**
     * Collects a class's constructor dependencies.
     *
     * @param string $className
     *   The fully qualified name of the class to collect dependencies for.
     *
     * @return mixed[]
     *   The class constructor's dependencies, keyed by constructor argument
     *   name.
     */
    public function collectDependencies($className);

}
